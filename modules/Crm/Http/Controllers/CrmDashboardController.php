<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Crm\Models\CrmActivity;
use Modules\Crm\Models\CrmDeal;
use Modules\Crm\Models\CrmLead;

class CrmDashboardController extends Controller
{
    public function index(): View
    {
        $totalLeads = CrmLead::count();
        $convertedLeads = CrmLead::where('status', CrmLead::STATUS_CONVERTED)->count();
        $openPipelineValue = (int) CrmDeal::whereIn('stage', [
            CrmDeal::STAGE_QUALIFIED,
            CrmDeal::STAGE_PROPOSAL,
            CrmDeal::STAGE_NEGOTIATION,
        ])->sum('value_amount');
        $wonRevenue = (int) CrmDeal::where('stage', CrmDeal::STAGE_WON)->sum('value_amount');
        $wonCount = CrmDeal::where('stage', CrmDeal::STAGE_WON)->count();
        $closedCount = CrmDeal::whereIn('stage', [CrmDeal::STAGE_WON, CrmDeal::STAGE_LOST])->count();

        $stats = [
            'total_leads' => $totalLeads,
            'conversion_rate' => $totalLeads > 0 ? (int) round(($convertedLeads / $totalLeads) * 100) : 0,
            'open_pipeline_value' => $openPipelineValue,
            'won_revenue' => $wonRevenue,
            'win_rate' => $closedCount > 0 ? (int) round(($wonCount / $closedCount) * 100) : 0,
            'open_activities' => CrmActivity::where('status', CrmActivity::STATUS_OPEN)->count(),
        ];

        $stages = [
            CrmDeal::STAGE_QUALIFIED => 'Qualified',
            CrmDeal::STAGE_PROPOSAL => 'Proposal',
            CrmDeal::STAGE_NEGOTIATION => 'Negotiation',
            CrmDeal::STAGE_WON => 'Won',
            CrmDeal::STAGE_LOST => 'Lost',
        ];

        $stageValues = [];
        foreach (array_keys($stages) as $stageKey) {
            $stageValues[] = (int) CrmDeal::where('stage', $stageKey)->sum('value_amount');
        }

        $sources = ['website', 'referral', 'cold_call', 'social', 'event'];
        $sourceCounts = [];
        foreach ($sources as $src) {
            $sourceCounts[] = CrmLead::where('source', $src)->count();
        }

        $chartData = [
            'stage_labels' => array_values($stages),
            'stage_values' => $stageValues,
            'source_labels' => ['Website', 'Referral', 'Cold Call', 'Social', 'Event'],
            'source_counts' => $sourceCounts,
        ];

        $topDeals = CrmDeal::with(['customer:id,name', 'owner:id,name'])
            ->whereIn('stage', [CrmDeal::STAGE_QUALIFIED, CrmDeal::STAGE_PROPOSAL, CrmDeal::STAGE_NEGOTIATION])
            ->orderByDesc('value_amount')
            ->limit(6)
            ->get();

        $upcomingActivities = CrmActivity::with(['lead:id,name', 'deal:id,title', 'assignee:id,name'])
            ->where('status', CrmActivity::STATUS_OPEN)
            ->orderBy('due_at')
            ->limit(6)
            ->get();

        return view('admin.crm.dashboard', compact('stats', 'chartData', 'topDeals', 'upcomingActivities'));
    }
}

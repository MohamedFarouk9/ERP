<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Pos\Models\PosSale;
use Modules\Pos\Models\PosSaleItem;
use Modules\Pos\Models\PosShift;

class PosDashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $todaySales = PosSale::where('sale_date', $today)->where('status', PosSale::STATUS_COMPLETED);

        $stats = [
            'today_revenue' => (int) (clone $todaySales)->sum('total_amount'),
            'today_tickets' => (clone $todaySales)->count(),
            'total_revenue' => (int) PosSale::where('status', PosSale::STATUS_COMPLETED)->sum('total_amount'),
            'total_tickets' => PosSale::where('status', PosSale::STATUS_COMPLETED)->count(),
            'open_shifts' => PosShift::where('status', PosShift::STATUS_OPEN)->count(),
            'avg_ticket' => (int) round((float) PosSale::where('status', PosSale::STATUS_COMPLETED)->avg('total_amount')),
        ];

        // 7-day daily sales
        $days = [];
        $dailySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $days[] = now()->subDays($i)->format('d M');
            $amt = (int) PosSale::where('sale_date', $d)->where('status', PosSale::STATUS_COMPLETED)->sum('total_amount');
            $dailySales[] = round($amt / 100, 2);
        }

        $paymentMix = PosSale::query()
            ->where('status', PosSale::STATUS_COMPLETED)
            ->select('payment_type', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_type')
            ->pluck('total', 'payment_type');

        $chartData = [
            'days' => $days,
            'daily_sales' => $dailySales,
            'payment_mix' => [
                'cash' => (int) ($paymentMix['cash'] ?? 0),
                'card' => (int) ($paymentMix['card'] ?? 0),
                'split' => (int) ($paymentMix['split'] ?? 0),
            ],
        ];

        $topItems = PosSaleItem::query()
            ->select('sku', 'product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(line_total) as total'))
            ->groupBy('sku', 'product_name')
            ->orderByDesc('qty')
            ->take(6)
            ->get();

        $recentSales = PosSale::with('cashier:id,name')->latest()->take(8)->get();

        return view('admin.pos.dashboard', compact('stats', 'chartData', 'topItems', 'recentSales'));
    }
}

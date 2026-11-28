<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Core\Services\TenantContext;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession() ? $request->session()->get('locale') : null;

        if (! $locale) {
            $tenant = app(TenantContext::class)->get();
            $locale = is_array($tenant?->settings) ? ($tenant->settings['locale'] ?? null) : null;
        }

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        $response = $next($request);

        if ($locale === 'ar' && $response instanceof JsonResponse) {
            $payload = $response->getData(true);
            if (is_array($payload) && isset($payload['message']) && is_string($payload['message'])) {
                $payload['message'] = $this->translateAjaxMessage($payload['message']);
                $response->setData($payload);
            }
        }

        return $response;
    }

    protected function translateAjaxMessage(string $message): string
    {
        if (Lang::hasForLocale($message, 'ar')) {
            return __($message);
        }

        $entityMap = [
            'Account' => 'الحساب',
            'Draft transaction' => 'مسودة القيد',
            'Transaction' => 'القيد',
            'Category' => 'التصنيف',
            'Product' => 'المنتج',
            'Warehouse' => 'المستودع',
            'Customer' => 'العميل',
            'Sales Order' => 'طلب البيع',
            'Invoice' => 'الفاتورة',
            'Payment method' => 'طريقة الدفع',
            'Payment' => 'الدفعة',
            'Refund' => 'المسترد',
            'Supplier' => 'المورد',
            'Purchase Order' => 'أمر الشراء',
            'Vendor Bill' => 'فاتورة المورد',
            'Employee' => 'الموظف',
            'Payroll run' => 'مسير الرواتب',
            'Register shift' => 'وردية الكاشير',
            'Sale' => 'عملية البيع',
            'Lead' => 'العميل المحتمل',
            'Deal' => 'الصفقة',
            'Staff member' => 'عضو الفريق',
            'Department' => 'القسم',
            'Team' => 'الفريق',
            'Role' => 'الدور',
            'System role' => 'دور النظام',
            'Feature' => 'الميزة',
            'Expense' => 'المصروف',
            'Tax rate' => 'معدل الضريبة',
            'Budget' => 'الميزانية',
        ];

        $patterns = [
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+created successfully\.?$/i' => fn (array $m) => 'تم إنشاء '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+updated(?: successfully)?\.?$/i' => fn (array $m) => 'تم تحديث '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+deleted successfully\.?$/i' => fn (array $m) => 'تم حذف '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+recorded successfully\.?$/i' => fn (array $m) => 'تم تسجيل '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+processed successfully\.?$/i' => fn (array $m) => 'تمت معالجة '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+marked as completed\.?$/i' => fn (array $m) => 'تم تحديد '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] كمكتمل.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+marked as paid\.?$/i' => fn (array $m) => 'تم صرف '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] بنجاح.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+posted to the general ledger\.?$/i' => fn (array $m) => "تم ترحيل القيد [{$m[2]}] إلى الأستاذ العام.",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+received & inventory stock updated\.?$/i' => fn (array $m) => "تم استلام أمر الشراء [{$m[2]}] وتحديث رصيد المخزون.",
            '/^Register shift\s+\[([^\]]+)\]\s+opened!?$/i' => fn (array $m) => "تم فتح وردية الكاشير [{$m[1]}] بنجاح!",
            '/^Register shift\s+\[([^\]]+)\]\s+closed & reconciled\.?$/i' => fn (array $m) => "تم إغلاق ومطابقة وردية الكاشير [{$m[1]}].",
            '/^Sale\s+\[([^\]]+)\]\s+completed!?$/i' => fn (array $m) => "تم إتمام عملية البيع [{$m[1]}] بنجاح!",
            '/^Lead\s+\[([^\]]+)\]\s+converted to Customer\s+\[([^\]]+)\]!?$/i' => fn (array $m) => "تم تحويل العميل المحتمل [{$m[1]}] إلى العميل [{$m[2]}] بنجاح!",
            '/^Deal\s+\[([^\]]+)\]\s+moved to\s+\[([^\]]+)\]\.?$/i' => fn (array $m) => "تم نقل الصفقة [{$m[1]}] إلى المرحلة [{$m[2]}].",
            '/^Payroll run\s+\[([^\]]+)\]\s+generated for\s+(\d+)\s+employees\.?$/i' => fn (array $m) => "تم إنشاء مسير الرواتب [{$m[1]}] لعدد {$m[2]} موظف.",
            '/^Feature\s+\[([^\]]+)\]\s+has been enabled\.?$/i' => fn (array $m) => "تم تفعيل الميزة [{$m[1]}] بنجاح.",
            '/^Feature\s+\[([^\]]+)\]\s+has been disabled\.?$/i' => fn (array $m) => "تم تعطيل الميزة [{$m[1]}] بنجاح.",
            '/^Assigned\s+\[([^\]]+)\]\s+to\s+\[([^\]]+)\]\.?$/i' => fn (array $m) => "تم تعيين [{$m[1]}] في الفريق [{$m[2]}].",
            '/^([A-Za-z ]+)\s+\[([^\]]+)\]\s+cannot be deleted/i' => fn (array $m) => 'لا يمكن حذف '.($entityMap[trim($m[1])] ?? trim($m[1]))." [{$m[2]}] لارتباطه بسجلات أخرى.",
            '/^Insufficient stock for product\s+\[([^\]]+)\]/i' => fn (array $m) => "لا يوجد رصيد كافٍ للمنتج [{$m[1]}] في المستودع.",
        ];

        foreach ($patterns as $regex => $handler) {
            if (preg_match($regex, $message, $matches)) {
                return $handler($matches);
            }
        }

        return $message;
    }
}

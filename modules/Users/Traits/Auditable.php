<?php

declare(strict_types=1);

namespace Modules\Users\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            self::recordAuditLog($model, 'created', null, $model->getAttributes());
        });

        static::updated(function (Model $model): void {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getDirty());
            $newValues = $model->getDirty();

            self::recordAuditLog($model, 'updated', $oldValues, $newValues);
        });

        static::deleted(function (Model $model): void {
            self::recordAuditLog($model, 'deleted', $model->getAttributes(), null);
        });
    }

    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    protected static function recordAuditLog(Model $model, string $event, ?array $oldValues, ?array $newValues): void
    {
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $tenantId = $tenantContext->id() ?? $model->getAttribute('tenant_id');

        AuditLog::create([
            'tenant_id' => $tenantId,
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => (string) $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}

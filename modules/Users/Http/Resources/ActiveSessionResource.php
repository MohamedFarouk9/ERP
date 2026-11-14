<?php

declare(strict_types=1);

namespace Modules\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\Models\UserActiveSession;

/**
 * @mixin UserActiveSession
 */
final class ActiveSessionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'ip_address' => $this->ip_address,
            'device_name' => $this->device_name,
            'user_agent' => $this->user_agent,
            'last_active_at' => $this->last_active_at->toIso8601String(),
            'logged_in_at' => $this->logged_in_at->toIso8601String(),
            'is_current' => $request->bearerToken() === $this->session_id || $request->session()->getId() === $this->session_id,
        ];
    }
}


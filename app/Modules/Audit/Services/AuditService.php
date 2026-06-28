<?php

declare(strict_types=1);

namespace App\Modules\Audit\Services;

use App\Modules\Audit\Models\AuditLog;
use App\Modules\Merchant\Services\ActiveMerchantContext;

class AuditService
{
    public function log(string $event, string $entityType, int $entityId, array $payload = []): AuditLog
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return AuditLog::create([
            'merchant_id' => $merchant->id,
            'event' => $event,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'payload' => $payload,
        ]);
    }
}

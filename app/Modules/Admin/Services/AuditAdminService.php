<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Audit\Models\AuditLog;
use App\Modules\Merchant\Services\ActiveMerchantContext;

class AuditAdminService
{
    use AdminResponse;

    public function list(array $filters = []): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $logs = AuditLog::forMerchant($merchant->id)
            ->when($filters['event'] ?? null, fn ($query, $event) => $query->where('event', $event))
            ->when($filters['entity_type'] ?? null, fn ($query, $entityType) => $query->where('entity_type', $entityType))
            ->latest()
            ->paginate($this->perPage($filters));

        return $this->paginated($logs);
    }
}

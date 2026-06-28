<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Audit\Models\AuditLog;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditAdminService
{
    public function list(AdminFilterDTO $filters): LengthAwarePaginator
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return AuditLog::forMerchant($merchant->id)
            ->when($filters->get('event'), fn ($query, $event) => $query->where('event', $event))
            ->when($filters->get('entity_type'), fn ($query, $entityType) => $query->where('entity_type', $entityType))
            ->latest()
            ->paginate($filters->perPage());
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Notifications\Models\NotificationLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationsAdminService
{
    public function list(AdminFilterDTO $filters): LengthAwarePaginator
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return NotificationLog::forMerchant($merchant->id)
            ->when($filters->get('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($filters->perPage());
    }
}

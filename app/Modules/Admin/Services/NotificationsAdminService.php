<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Notifications\Models\NotificationLog;

class NotificationsAdminService
{
    use AdminResponse;

    public function list(array $filters = []): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $notifications = NotificationLog::forMerchant($merchant->id)
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($this->perPage($filters));

        return $this->paginated($notifications);
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\OrderService;

class OrdersAdminService
{
    use AdminResponse;

    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function list(array $filters = []): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $orders = Order::forMerchant($merchant->id)
            ->with(['items.productVariant', 'payment'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate($this->perPage($filters));

        return $this->paginated($orders);
    }

    public function view(int $orderId): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return $this->single(
            Order::forMerchant($merchant->id)
                ->with(['items.productVariant', 'payment'])
                ->findOrFail($orderId)
        );
    }

    public function cancel(int $orderId): array
    {
        return $this->single(
            $this->orderService->cancelOrder($orderId)
        );
    }
}

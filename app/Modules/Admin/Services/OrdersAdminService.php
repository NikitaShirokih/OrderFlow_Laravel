<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Orders\DTO\CancelOrderDTO;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\OrderService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrdersAdminService
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function list(AdminFilterDTO $filters): LengthAwarePaginator
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Order::forMerchant($merchant->id)
            ->with(['items.productVariant', 'payment'])
            ->when($filters->get('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($filters->get('date_from'), fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters->get('date_to'), fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate($filters->perPage());
    }

    public function view(int $orderId): Order
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Order::forMerchant($merchant->id)
            ->with(['items.productVariant', 'payment'])
            ->findOrFail($orderId);
    }

    public function cancel(int $orderId): Order
    {
        return $this->orderService->cancelOrder(new CancelOrderDTO($orderId));
    }
}

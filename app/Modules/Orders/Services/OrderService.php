<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Catalog\Services\StockService;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Orders\Enums\OrderStatus;
use App\Modules\Orders\Events\OrderCanceled;
use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Events\OrderReserved;
use App\Modules\Orders\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly StockService $stockService
    ) {
    }

    public function create(array $items): Order
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return DB::transaction(function () use ($items, $merchant): Order {
            $order = Order::create([
                'merchant_id' => $merchant->id,
                'user_id' => null,
                'status' => OrderStatus::NEW,
                'total_amount' => 0,
            ]);

            $totalAmount = 0.0;

            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $variant = ProductVariant::forMerchant($merchant->id)
                    ->findOrFail((int) $item['variant_id']);

                $price = (float) $variant->price;
                $totalAmount += $price * $quantity;

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            $order->update([
                'total_amount' => $totalAmount,
            ]);

            $order = $order->load('items');

            event(new OrderCreated($order->id, $merchant->id));

            return $order;
        });
    }

    public function reserveOrder(int $orderId): Order
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return DB::transaction(function () use ($merchant, $orderId): Order {
            $order = Order::forMerchant($merchant->id)
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status !== OrderStatus::NEW) {
                throw ValidationException::withMessages([
                    'order_id' => ['Only new orders can be reserved.'],
                ]);
            }

            foreach ($order->items as $item) {
                ProductVariant::forMerchant($merchant->id)->findOrFail($item->product_variant_id);
                $this->stockService->reserve($item->product_variant_id, $item->quantity);
            }

            $order->update([
                'status' => OrderStatus::RESERVED,
            ]);

            $order = $order->refresh()->load('items');

            event(new OrderReserved($order->id, $merchant->id));

            return $order;
        });
    }

    public function cancelOrder(int $orderId): Order
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return DB::transaction(function () use ($merchant, $orderId): Order {
            $order = Order::forMerchant($merchant->id)
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status === OrderStatus::PAID) {
                throw ValidationException::withMessages([
                    'order_id' => ['Paid orders cannot be canceled.'],
                ]);
            }

            if ($order->status === OrderStatus::CANCELED) {
                throw ValidationException::withMessages([
                    'order_id' => ['Order is already canceled.'],
                ]);
            }

            if ($order->status === OrderStatus::RESERVED) {
                foreach ($order->items as $item) {
                    ProductVariant::forMerchant($merchant->id)->findOrFail($item->product_variant_id);
                    $this->stockService->release($item->product_variant_id, $item->quantity);
                }
            }

            $order->update([
                'status' => OrderStatus::CANCELED,
            ]);

            $order = $order->refresh()->load('items');

            event(new OrderCanceled($order->id, $merchant->id));

            return $order;
        });
    }
}

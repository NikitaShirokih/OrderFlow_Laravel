<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Catalog\Services\StockService;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Orders\Models\Order;
use Illuminate\Support\Facades\DB;

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
                'status' => 'new',
                'total_amount' => 0,
            ]);

            $totalAmount = 0.0;

            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $variant = ProductVariant::forMerchant($merchant->id)
                    ->findOrFail((int) $item['variant_id']);

                $this->stockService->reserve($variant->id, $quantity);

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

            return $order->load('items');
        });
    }
}

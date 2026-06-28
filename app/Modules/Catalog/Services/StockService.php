<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Catalog\Models\Stock;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function setStock(int $variantId, int $quantity): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($variantId);

        return DB::transaction(function () use ($merchant, $quantity, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->first();

            if (!$stock) {
                return Stock::create([
                    'product_variant_id' => $variant->id,
                    'merchant_id' => $merchant->id,
                    'quantity' => $quantity,
                    'reserved' => 0,
                ]);
            }

            if ($quantity < $stock->reserved) {
                throw ValidationException::withMessages([
                    'quantity' => ['Quantity cannot be less than reserved stock.'],
                ]);
            }

            $stock->update([
                'quantity' => $quantity,
            ]);

            return $stock->refresh();
        });
    }

    public function reserve(int $variantId, int $quantity): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($variantId);

        return DB::transaction(function () use ($merchant, $quantity, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($quantity > $stock->available) {
                throw ValidationException::withMessages([
                    'quantity' => ['Quantity exceeds available stock.'],
                ]);
            }

            $stock->update([
                'reserved' => $stock->reserved + $quantity,
            ]);

            return $stock->refresh();
        });
    }

    public function release(int $variantId, int $quantity): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($variantId);

        return DB::transaction(function () use ($merchant, $quantity, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stock->update([
                'reserved' => max(0, $stock->reserved - $quantity),
            ]);

            return $stock->refresh();
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\DTO\ReleaseStockDTO;
use App\Modules\Catalog\DTO\ReserveStockDTO;
use App\Modules\Catalog\DTO\SetStockDTO;
use App\Modules\Catalog\Events\StockReleased;
use App\Modules\Catalog\Events\StockReserved;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Catalog\Models\Stock;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function setStock(SetStockDTO $data): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($data->variantId);

        return DB::transaction(function () use ($data, $merchant, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->first();

            if (!$stock) {
                return Stock::create([
                    'product_variant_id' => $variant->id,
                    'merchant_id' => $merchant->id,
                    'quantity' => $data->quantity,
                    'reserved' => 0,
                ]);
            }

            if ($data->quantity < $stock->reserved) {
                throw ValidationException::withMessages([
                    'quantity' => ['Quantity cannot be less than reserved stock.'],
                ]);
            }

            $stock->update([
                'quantity' => $data->quantity,
            ]);

            return $stock->refresh();
        });
    }

    public function reserve(ReserveStockDTO $data): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($data->variantId);

        return DB::transaction(function () use ($data, $merchant, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->quantity > $stock->available) {
                throw ValidationException::withMessages([
                    'quantity' => ['Quantity exceeds available stock.'],
                ]);
            }

            $stock->update([
                'reserved' => $stock->reserved + $data->quantity,
            ]);

            $stock = $stock->refresh();

            event(new StockReserved($stock->id, $variant->id, $merchant->id, $data->quantity));

            return $stock;
        });
    }

    public function release(ReleaseStockDTO $data): Stock
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $variant = ProductVariant::forMerchant($merchant->id)->findOrFail($data->variantId);

        return DB::transaction(function () use ($data, $merchant, $variant): Stock {
            $stock = Stock::forMerchant($merchant->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stock->update([
                'reserved' => max(0, $stock->reserved - $data->quantity),
            ]);

            $stock = $stock->refresh();

            event(new StockReleased($stock->id, $variant->id, $merchant->id, $data->quantity));

            return $stock;
        });
    }
}

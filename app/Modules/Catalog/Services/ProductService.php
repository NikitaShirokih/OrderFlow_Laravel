<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Merchant\Services\ActiveMerchantContext;

class ProductService
{
    public function create(array $data): Product
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::create([
            'merchant_id' => $merchant->id,
            'name' => $data['name'],
            'sku' => $data['sku'],
            'status' => $data['status'] ?? 'draft',
        ]);
    }

    public function list(): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::query()
            ->forMerchant($merchant->id)
            ->get()
            ->toArray();
    }
}

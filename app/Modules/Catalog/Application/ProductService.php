<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\Services;

use App\Modules\Merchant\Application\Context\ActiveMerchantContext;
use App\Modules\Catalog\Domain\Models\Product;

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
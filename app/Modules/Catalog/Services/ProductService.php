<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\DTO\CreateProductDTO;
use App\Modules\Catalog\Models\Product;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function create(CreateProductDTO $data): Product
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::create([
            'merchant_id' => $merchant->id,
            'name' => $data->name,
            'sku' => $data->sku,
            'status' => 'draft',
        ]);
    }

    public function list(): Collection
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::forMerchant($merchant->id)
            ->get();
    }
}

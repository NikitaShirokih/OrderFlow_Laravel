<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\DTO\CreateProductVariantDTO;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Merchant\Services\ActiveMerchantContext;

class ProductVariantService
{
    public function create(CreateProductVariantDTO $data): ProductVariant
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $product = Product::forMerchant($merchant->id)->findOrFail($data->productId);

        return ProductVariant::create([
            'product_id' => $product->id,
            'merchant_id' => $merchant->id,
            'name' => $data->name,
            'sku' => $data->sku,
            'price' => $data->price,
            'attributes' => $data->attributes,
        ]);
    }
}

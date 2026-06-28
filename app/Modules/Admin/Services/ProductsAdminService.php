<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Merchant\Services\ActiveMerchantContext;

class ProductsAdminService
{
    use AdminResponse;

    public function list(array $filters = []): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $products = Product::forMerchant($merchant->id)
            ->with('variants.stock')
            ->latest()
            ->paginate($this->perPage($filters));

        return $this->paginated($products);
    }

    public function view(int $productId): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return $this->single(
            Product::forMerchant($merchant->id)
                ->with('variants.stock')
                ->findOrFail($productId)
        );
    }
}

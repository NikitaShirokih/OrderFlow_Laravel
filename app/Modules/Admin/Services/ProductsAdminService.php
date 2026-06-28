<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Catalog\Models\Product;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsAdminService
{
    public function list(AdminFilterDTO $filters): LengthAwarePaginator
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::forMerchant($merchant->id)
            ->with('variants.stock')
            ->latest()
            ->paginate($filters->perPage());
    }

    public function view(int $productId): Product
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Product::forMerchant($merchant->id)
            ->with('variants.stock')
            ->findOrFail($productId);
    }
}

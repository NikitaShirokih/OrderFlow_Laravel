<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Admin\Services\ProductsAdminService;
use App\Modules\Catalog\Resources\ProductResource;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsAdminController extends Controller
{
    public function index(Request $request, ProductsAdminService $service): JsonResponse
    {
        $products = $service->list(AdminFilterDTO::fromQuery($request->query()));

        return ApiResponse::success(
            ProductResource::collection($products),
            meta: ApiResponse::paginationMeta($products)
        );
    }

    public function show(int $product, ProductsAdminService $service): JsonResponse
    {
        return ApiResponse::success(new ProductResource($service->view($product)));
    }
}

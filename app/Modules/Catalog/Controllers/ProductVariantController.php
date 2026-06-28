<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\DTO\CreateProductVariantDTO;
use App\Modules\Catalog\Resources\ProductVariantResource;
use App\Modules\Catalog\Services\ProductVariantService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, ProductVariantService $service): JsonResponse
    {
        $dto = CreateProductVariantDTO::fromValidated($request->validate([
            'product_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'attributes' => ['nullable', 'array'],
        ]));

        return ApiResponse::success(new ProductVariantResource($service->create($dto)));
    }
}

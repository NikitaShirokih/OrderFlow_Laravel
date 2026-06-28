<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\DTO\CreateProductDTO;
use App\Modules\Catalog\Resources\ProductResource;
use App\Modules\Catalog\Services\ProductService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request, ProductService $service): JsonResponse
    {
        $dto = CreateProductDTO::fromValidated($request->validate([
            'name' => 'required|string',
            'sku' => 'required|string',
        ]));

        return ApiResponse::success(new ProductResource($service->create($dto)));
    }

    public function index(ProductService $service): JsonResponse
    {
        return ApiResponse::success(ProductResource::collection($service->list()));
    }
}

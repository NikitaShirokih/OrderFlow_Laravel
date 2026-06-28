<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Services\ProductVariantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, ProductVariantService $service): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'attributes' => ['nullable', 'array'],
        ]);

        $variant = $service->create((int) $data['product_id'], $data);

        return response()->json($variant);
    }
}

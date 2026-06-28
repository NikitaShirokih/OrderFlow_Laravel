<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Services\ProductsAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsAdminController extends Controller
{
    public function index(Request $request, ProductsAdminService $service): JsonResponse
    {
        return response()->json($service->list($request->query()));
    }

    public function show(int $product, ProductsAdminService $service): JsonResponse
    {
        return response()->json($service->view($product));
    }
}

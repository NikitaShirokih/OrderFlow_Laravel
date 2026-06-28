<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\DTO\ReleaseStockDTO;
use App\Modules\Catalog\DTO\ReserveStockDTO;
use App\Modules\Catalog\DTO\SetStockDTO;
use App\Modules\Catalog\Resources\StockResource;
use App\Modules\Catalog\Services\StockService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function set(Request $request, StockService $service): JsonResponse
    {
        $dto = SetStockDTO::fromValidated($request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]));

        return ApiResponse::success(new StockResource($service->setStock($dto)));
    }

    public function reserve(Request $request, StockService $service): JsonResponse
    {
        $dto = ReserveStockDTO::fromValidated($request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]));

        return ApiResponse::success(new StockResource($service->reserve($dto)));
    }

    public function release(Request $request, StockService $service): JsonResponse
    {
        $dto = ReleaseStockDTO::fromValidated($request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]));

        return ApiResponse::success(new StockResource($service->release($dto)));
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function set(Request $request, StockService $service): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        return response()->json(
            $service->setStock((int) $data['variant_id'], (int) $data['quantity'])
        );
    }

    public function reserve(Request $request, StockService $service): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json(
            $service->reserve((int) $data['variant_id'], (int) $data['quantity'])
        );
    }

    public function release(Request $request, StockService $service): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json(
            $service->release((int) $data['variant_id'], (int) $data['quantity'])
        );
    }
}

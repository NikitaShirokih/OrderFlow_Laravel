<?php

declare(strict_types=1);

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Payments\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $service): JsonResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json(
            $service->create($data['items'])
        );
    }

    public function reserve(Request $request, OrderService $service): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer'],
        ]);

        return response()->json(
            $service->reserveOrder((int) $data['order_id'])
        );
    }

    public function pay(Request $request, PaymentService $service): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer'],
        ]);

        return response()->json(
            $service->payByOrderId((int) $data['order_id'])
        );
    }

    public function cancel(Request $request, OrderService $service): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer'],
        ]);

        return response()->json(
            $service->cancelOrder((int) $data['order_id'])
        );
    }
}

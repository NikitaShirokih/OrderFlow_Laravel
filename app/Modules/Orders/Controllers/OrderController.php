<?php

declare(strict_types=1);

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\DTO\CancelOrderDTO;
use App\Modules\Orders\DTO\CreateOrderDTO;
use App\Modules\Orders\DTO\ReserveOrderDTO;
use App\Modules\Orders\Resources\OrderResource;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Payments\DTO\PayOrderDTO;
use App\Modules\Payments\Resources\PaymentResource;
use App\Modules\Payments\Services\PaymentService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $service): JsonResponse
    {
        $dto = CreateOrderDTO::fromValidated($request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]));

        return ApiResponse::success(new OrderResource($service->create($dto)));
    }

    public function reserve(Request $request, OrderService $service): JsonResponse
    {
        $dto = ReserveOrderDTO::fromValidated($request->validate([
            'order_id' => ['required', 'integer'],
        ]));

        return ApiResponse::success(new OrderResource($service->reserveOrder($dto)));
    }

    public function pay(Request $request, PaymentService $service): JsonResponse
    {
        $dto = PayOrderDTO::fromValidated($request->validate([
            'order_id' => ['required', 'integer'],
        ]));

        return ApiResponse::success(new PaymentResource($service->payByOrderId($dto)));
    }

    public function cancel(Request $request, OrderService $service): JsonResponse
    {
        $dto = CancelOrderDTO::fromValidated($request->validate([
            'order_id' => ['required', 'integer'],
        ]));

        return ApiResponse::success(new OrderResource($service->cancelOrder($dto)));
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\DTO\PayOrderDTO;
use App\Modules\Payments\Resources\PaymentResource;
use App\Modules\Payments\Services\PaymentService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, PaymentService $service): JsonResponse
    {
        $dto = PayOrderDTO::fromValidated($request->validate([
            'order_id' => ['required', 'integer'],
        ]));

        return ApiResponse::success(new PaymentResource($service->payByOrderId($dto)));
    }
}

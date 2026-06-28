<?php

declare(strict_types=1);

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, PaymentService $service): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer'],
        ]);

        return response()->json(
            $service->payByOrderId((int) $data['order_id'])
        );
    }
}

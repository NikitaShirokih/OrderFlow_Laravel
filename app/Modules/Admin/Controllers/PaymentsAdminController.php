<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Admin\Services\PaymentsAdminService;
use App\Modules\Payments\Resources\PaymentResource;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsAdminController extends Controller
{
    public function index(Request $request, PaymentsAdminService $service): JsonResponse
    {
        $payments = $service->list(AdminFilterDTO::fromQuery($request->query()));

        return ApiResponse::success(
            PaymentResource::collection($payments),
            meta: ApiResponse::paginationMeta($payments)
        );
    }

    public function show(int $payment, PaymentsAdminService $service): JsonResponse
    {
        return ApiResponse::success(new PaymentResource($service->view($payment)));
    }

    public function status(int $payment, PaymentsAdminService $service): JsonResponse
    {
        return ApiResponse::success(new PaymentResource($service->view($payment)));
    }
}

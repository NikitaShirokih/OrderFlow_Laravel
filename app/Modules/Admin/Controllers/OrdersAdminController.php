<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Admin\Services\OrdersAdminService;
use App\Modules\Orders\Resources\OrderResource;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersAdminController extends Controller
{
    public function index(Request $request, OrdersAdminService $service): JsonResponse
    {
        $orders = $service->list(AdminFilterDTO::fromQuery($request->query()));

        return ApiResponse::success(
            OrderResource::collection($orders),
            meta: ApiResponse::paginationMeta($orders)
        );
    }

    public function show(int $order, OrdersAdminService $service): JsonResponse
    {
        return ApiResponse::success(new OrderResource($service->view($order)));
    }

    public function cancel(int $order, OrdersAdminService $service): JsonResponse
    {
        return ApiResponse::success(new OrderResource($service->cancel($order)));
    }
}

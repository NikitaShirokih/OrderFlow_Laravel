<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Services\OrdersAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersAdminController extends Controller
{
    public function index(Request $request, OrdersAdminService $service): JsonResponse
    {
        return response()->json($service->list($request->query()));
    }

    public function show(int $order, OrdersAdminService $service): JsonResponse
    {
        return response()->json($service->view($order));
    }

    public function cancel(int $order, OrdersAdminService $service): JsonResponse
    {
        return response()->json($service->cancel($order));
    }
}

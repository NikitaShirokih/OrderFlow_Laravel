<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Services\PaymentsAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsAdminController extends Controller
{
    public function index(Request $request, PaymentsAdminService $service): JsonResponse
    {
        return response()->json($service->list($request->query()));
    }

    public function show(int $payment, PaymentsAdminService $service): JsonResponse
    {
        return response()->json($service->view($payment));
    }

    public function status(int $payment, PaymentsAdminService $service): JsonResponse
    {
        return response()->json($service->view($payment));
    }
}

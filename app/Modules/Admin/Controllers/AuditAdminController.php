<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Services\AuditAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditAdminController extends Controller
{
    public function index(Request $request, AuditAdminService $service): JsonResponse
    {
        return response()->json($service->list($request->query()));
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Admin\Services\AuditAdminService;
use App\Modules\Audit\Resources\AuditLogResource;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditAdminController extends Controller
{
    public function index(Request $request, AuditAdminService $service): JsonResponse
    {
        $logs = $service->list(AdminFilterDTO::fromQuery($request->query()));

        return ApiResponse::success(
            AuditLogResource::collection($logs),
            meta: ApiResponse::paginationMeta($logs)
        );
    }
}

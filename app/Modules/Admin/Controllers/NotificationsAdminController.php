<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Admin\Services\NotificationsAdminService;
use App\Modules\Notifications\Resources\NotificationLogResource;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsAdminController extends Controller
{
    public function index(Request $request, NotificationsAdminService $service): JsonResponse
    {
        $notifications = $service->list(AdminFilterDTO::fromQuery($request->query()));

        return ApiResponse::success(
            NotificationLogResource::collection($notifications),
            meta: ApiResponse::paginationMeta($notifications)
        );
    }
}

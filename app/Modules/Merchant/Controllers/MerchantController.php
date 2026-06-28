<?php

namespace App\Modules\Merchant\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Requests\StoreMerchantRequest;
use App\Modules\Merchant\Resources\MerchantResource;
use App\Modules\Merchant\Services\MerchantService;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function store(
        StoreMerchantRequest $request,
        MerchantService $service
    ) {
        $this->authorize('create', Merchant::class);

        $merchant = $service->create(
            $request->user()->id,
            $request->validated()
        );

        return ApiResponse::success(new MerchantResource($merchant));
    }

    public function index(
        Request $request,
        MerchantService $service
    ) {
        $this->authorize('viewAny', Merchant::class);

        return ApiResponse::success(MerchantResource::collection($service->listForUser($request->user()->id)));
    }
}

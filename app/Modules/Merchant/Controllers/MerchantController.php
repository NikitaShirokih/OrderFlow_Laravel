<?php

namespace App\Modules\Merchant\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Requests\StoreMerchantRequest;
use App\Modules\Merchant\Services\MerchantService;
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

        return response()->json([
            'id' => $merchant->id,
            'name' => $merchant->name,
        ]);
    }

    public function index(
        Request $request,
        MerchantService $service
    ) {
        $this->authorize('viewAny', Merchant::class);

        return response()->json(
            $service->listForUser($request->user()->id)
        );
    }
}

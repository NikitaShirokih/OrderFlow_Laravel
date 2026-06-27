<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerchantRequest;
use App\Services\Merchant\MerchantService;

class MerchantController extends Controller
{
    public function store(
        StoreMerchantRequest $request,
        MerchantService $service
    ) {
        $merchant = $service->create(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'id' => $merchant->id,
            'name' => $merchant->name,
        ]);
    }
}
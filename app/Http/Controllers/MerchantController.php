<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerchantRequest;
use App\Services\Merchant\MerchantService;
use GuzzleHttp\Psr7\Request;
use App\Models\Merchant;


class MerchantController extends Controller
{
    public function store(
        StoreMerchantRequest $request,
        MerchantService $service
    ) {

        $this->authorize('create, Merchant::class');

        $merchant = $service->create(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'id' => $merchant->id,
            'name' => $merchant->name,
        ]);
    }
    
    public function index(Request $request, MerchantService $service)
    {

    $this->authorize('viewAny', Merchant::class);

    return response()->json(
        $service->listForUser($request->user()->id)
    );
}
}
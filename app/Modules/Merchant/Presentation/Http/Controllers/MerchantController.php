<?php

namespace App\Modules\Merchant\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Application\Services\MerchantService;
use App\Modules\Merchant\Domain\Models\Merchant;
use App\Modules\Merchant\Presentation\Http\Requests\StoreMerchantRequest;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function store(
        StoreMerchantRequest $request,
        MerchantService $service
    ) {
        $this->authorize('create', Merchant::class);

        $merchant = $service->create($request->validated());

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
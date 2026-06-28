<?php

declare(strict_types=1);

namespace App\Modules\MMerchant\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Merchant\Application\Context\ActiveMerchantContext;

class SetActiveMerchant
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $merchant = $user->merchants()->first();

        app(ActiveMerchantContext::class)->set($merchant);

        return $next($request);
    }
}
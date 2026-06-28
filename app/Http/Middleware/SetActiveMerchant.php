<?php

namespace App\Http\Middleware;

use App\Modules\Merchant\Services\ActiveMerchantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $merchant = $user->merchants()->first();

        app(ActiveMerchantContext::class)->set($merchant);

        return $next($request);
    }
}

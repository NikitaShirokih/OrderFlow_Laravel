<?php

namespace App\Providers;

use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Policies\MerchantPolicy;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Merchant::class => MerchantPolicy::class,
    ];

    public function register(): void
    {
        $this->app->singleton(ActiveMerchantContext::class);
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

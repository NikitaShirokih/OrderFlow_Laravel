<?php

namespace App\Providers;

use App\Modules\Merchant\Domain\Models\Merchant;
use App\Modules\Merchant\Domain\Policies\MerchantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Merchant::class => MerchantPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

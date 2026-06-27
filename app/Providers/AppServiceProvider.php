<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Policies\MerchantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Merchant::class => MerchantPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
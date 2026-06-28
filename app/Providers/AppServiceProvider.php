<?php

namespace App\Providers;

use App\Modules\Catalog\Events\StockReleased;
use App\Modules\Catalog\Events\StockReserved;
use App\Modules\Catalog\Listeners\LogStockEvent;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Policies\MerchantPolicy;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Orders\Events\OrderCanceled;
use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Events\OrderPaid;
use App\Modules\Orders\Events\OrderReserved;
use App\Modules\Orders\Listeners\LogOrderEvent;
use App\Modules\Payments\Events\PaymentCreated;
use App\Modules\Payments\Events\PaymentSucceeded;
use App\Modules\Payments\Listeners\LogPaymentEvent;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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

        Event::listen(OrderCreated::class, LogOrderEvent::class);
        Event::listen(OrderReserved::class, LogOrderEvent::class);
        Event::listen(OrderPaid::class, LogOrderEvent::class);
        Event::listen(OrderCanceled::class, LogOrderEvent::class);
        Event::listen(PaymentCreated::class, LogPaymentEvent::class);
        Event::listen(PaymentSucceeded::class, LogPaymentEvent::class);
        Event::listen(StockReserved::class, LogStockEvent::class);
        Event::listen(StockReleased::class, LogStockEvent::class);
    }
}

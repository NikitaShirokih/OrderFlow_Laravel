<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Listeners;

use App\Modules\Notifications\Jobs\SendOrderCreatedEmailJob;
use App\Modules\Notifications\Jobs\SendOrderPaidEmailJob;
use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Events\OrderPaid;

class SendOrderNotification
{
    public function handle(object $event): void
    {
        match ($event::class) {
            OrderCreated::class => SendOrderCreatedEmailJob::dispatch($event->merchantId, $event->orderId),
            OrderPaid::class => SendOrderPaidEmailJob::dispatch($event->merchantId, $event->orderId),
            default => null,
        };
    }
}

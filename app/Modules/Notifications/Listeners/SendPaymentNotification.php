<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Listeners;

use App\Modules\Notifications\Jobs\SendPaymentSucceededEmailJob;

class SendPaymentNotification
{
    public function handle(object $event): void
    {
        SendPaymentSucceededEmailJob::dispatch(
            $event->merchantId,
            $event->paymentId,
            $event->orderId
        );
    }
}

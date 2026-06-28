<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Listeners;

use App\Modules\Notifications\Services\NotificationService;

class SendPaymentNotification
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {
    }

    public function handle(object $event): void
    {
        $this->notificationService->send('payment_succeeded', [
            'payment_id' => $event->paymentId,
            'order_id' => $event->orderId,
        ]);
    }
}

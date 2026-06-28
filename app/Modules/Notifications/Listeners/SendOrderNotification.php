<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Listeners;

use App\Modules\Notifications\Services\NotificationService;
use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Events\OrderPaid;

class SendOrderNotification
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {
    }

    public function handle(object $event): void
    {
        match ($event::class) {
            OrderCreated::class => $this->notificationService->send('order_created', [
                'order_id' => $event->orderId,
            ]),
            OrderPaid::class => $this->notificationService->send('order_paid', [
                'order_id' => $event->orderId,
            ]),
            default => null,
        };
    }
}

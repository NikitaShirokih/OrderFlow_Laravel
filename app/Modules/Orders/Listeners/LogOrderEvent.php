<?php

declare(strict_types=1);

namespace App\Modules\Orders\Listeners;

use App\Modules\Audit\Jobs\WriteAuditLogJob;
use Illuminate\Support\Facades\Log;

class LogOrderEvent
{
    public function handle(object $event): void
    {
        $payload = get_object_vars($event) + [
            'event' => $event::class,
        ];

        WriteAuditLogJob::dispatch(
            merchantId: $event->merchantId,
            event: class_basename($event),
            entityType: 'Order',
            entityId: $event->orderId,
            payload: $payload
        );

        Log::info('Order audit job dispatched.', $payload);
    }
}

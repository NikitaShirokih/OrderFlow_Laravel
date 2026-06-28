<?php

declare(strict_types=1);

namespace App\Modules\Payments\Listeners;

use App\Modules\Audit\Jobs\WriteAuditLogJob;
use Illuminate\Support\Facades\Log;

class LogPaymentEvent
{
    public function handle(object $event): void
    {
        $payload = get_object_vars($event) + [
            'event' => $event::class,
        ];

        WriteAuditLogJob::dispatch(
            merchantId: $event->merchantId,
            event: class_basename($event),
            entityType: 'Payment',
            entityId: $event->paymentId,
            payload: $payload
        );

        Log::info('Payment audit job dispatched.', $payload);
    }
}

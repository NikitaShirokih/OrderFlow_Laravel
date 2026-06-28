<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Listeners;

use App\Modules\Audit\Jobs\WriteAuditLogJob;
use Illuminate\Support\Facades\Log;

class LogStockEvent
{
    public function handle(object $event): void
    {
        $payload = get_object_vars($event) + [
            'event' => $event::class,
        ];

        WriteAuditLogJob::dispatch(
            merchantId: $event->merchantId,
            event: class_basename($event),
            entityType: 'Stock',
            entityId: $event->stockId,
            payload: $payload
        );

        Log::info('Stock audit job dispatched.', $payload);
    }
}

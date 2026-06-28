<?php

declare(strict_types=1);

namespace App\Modules\Orders\Listeners;

use App\Modules\Audit\Services\AuditService;
use Illuminate\Support\Facades\Log;

class LogOrderEvent
{
    public function __construct(
        private readonly AuditService $auditService
    ) {
    }

    public function handle(object $event): void
    {
        $payload = get_object_vars($event) + [
            'event' => $event::class,
        ];

        $this->auditService->log(
            event: class_basename($event),
            entityType: 'Order',
            entityId: $event->orderId,
            payload: $payload
        );

        Log::info('Order event dispatched.', $payload);
    }
}

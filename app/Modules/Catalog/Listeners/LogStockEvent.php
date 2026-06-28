<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Listeners;

use App\Modules\Audit\Services\AuditService;
use Illuminate\Support\Facades\Log;

class LogStockEvent
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
            entityType: 'Stock',
            entityId: $event->stockId,
            payload: $payload
        );

        Log::info('Stock event dispatched.', $payload);
    }
}

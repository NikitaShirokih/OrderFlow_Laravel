<?php

declare(strict_types=1);

namespace App\Modules\Audit\Jobs;

use App\Modules\Audit\Services\AuditService;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class WriteAuditLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $merchantId,
        public readonly string $event,
        public readonly string $entityType,
        public readonly int $entityId,
        public readonly array $payload = []
    ) {
        $this->onQueue('default');
    }

    public function handle(AuditService $auditService): void
    {
        app(ActiveMerchantContext::class)->set(
            Merchant::findOrFail($this->merchantId)
        );

        $auditService->log(
            event: $this->event,
            entityType: $this->entityType,
            entityId: $this->entityId,
            payload: $this->payload
        );
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Audit job failed.', [
            'event' => $this->event,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
            'merchant_id' => $this->merchantId,
            'error' => $exception->getMessage(),
        ]);
    }
}

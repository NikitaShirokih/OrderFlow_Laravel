<?php

declare(strict_types=1);

namespace App\Modules\Integrations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $merchantId,
        public readonly string $event,
        public readonly array $payload = []
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        Log::info('Webhook job placeholder executed.', [
            'merchant_id' => $this->merchantId,
            'event' => $this->event,
            'payload' => $this->payload,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Webhook job failed.', [
            'merchant_id' => $this->merchantId,
            'event' => $this->event,
            'error' => $exception->getMessage(),
        ]);
    }
}

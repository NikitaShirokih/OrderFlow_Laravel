<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Jobs;

use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Notifications\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendOrderCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $merchantId,
        public readonly int $orderId
    ) {
        $this->onQueue('default');
    }

    public function handle(NotificationService $notificationService): void
    {
        app(ActiveMerchantContext::class)->set(
            Merchant::findOrFail($this->merchantId)
        );

        $notificationService->send('order_created', [
            'order_id' => $this->orderId,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Order created email job failed.', [
            'order_id' => $this->orderId,
            'merchant_id' => $this->merchantId,
            'error' => $exception->getMessage(),
        ]);
    }
}

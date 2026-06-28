<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Payments\Models\Payment;

class PaymentsAdminService
{
    use AdminResponse;

    public function list(array $filters = []): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $payments = Payment::forMerchant($merchant->id)
            ->with('order')
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($this->perPage($filters));

        return $this->paginated($payments);
    }

    public function view(int $paymentId): array
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return $this->single(
            Payment::forMerchant($merchant->id)
                ->with('order')
                ->findOrFail($paymentId)
        );
    }
}

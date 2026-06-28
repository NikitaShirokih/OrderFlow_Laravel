<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTO\AdminFilterDTO;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Payments\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentsAdminService
{
    public function list(AdminFilterDTO $filters): LengthAwarePaginator
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Payment::forMerchant($merchant->id)
            ->with('order')
            ->when($filters->get('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($filters->perPage());
    }

    public function view(int $paymentId): Payment
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        return Payment::forMerchant($merchant->id)
            ->with('order')
            ->findOrFail($paymentId);
    }
}

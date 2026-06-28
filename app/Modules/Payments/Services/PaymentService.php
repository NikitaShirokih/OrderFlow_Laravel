<?php

declare(strict_types=1);

namespace App\Modules\Payments\Services;

use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Payments\DTO\PayOrderDTO;
use App\Modules\Orders\Enums\OrderStatus;
use App\Modules\Orders\Events\OrderPaid;
use App\Modules\Orders\Models\Order;
use App\Modules\Payments\Enums\PaymentStatus;
use App\Modules\Payments\Events\PaymentCreated;
use App\Modules\Payments\Events\PaymentSucceeded;
use App\Modules\Payments\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function payByOrderId(PayOrderDTO $data): Payment
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        $order = Order::forMerchant($merchant->id)->findOrFail($data->orderId);

        return $this->pay($order);
    }

    public function pay(Order $order): Payment
    {
        $merchant = app(ActiveMerchantContext::class)->get();

        if ($order->merchant_id !== $merchant->id) {
            throw ValidationException::withMessages([
                'order_id' => ['Order does not belong to the active merchant.'],
            ]);
        }

        return DB::transaction(function () use ($merchant, $order): Payment {
            $order = Order::forMerchant($merchant->id)
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($order->status === OrderStatus::PAID) {
                throw ValidationException::withMessages([
                    'order_id' => ['Order is already paid.'],
                ]);
            }

            if ($order->status !== OrderStatus::RESERVED) {
                throw ValidationException::withMessages([
                    'order_id' => ['Only reserved orders can be paid.'],
                ]);
            }

            $payment = Payment::forMerchant($merchant->id)
                ->where('order_id', $order->id)
                ->lockForUpdate()
                ->first();

            if ($payment && $payment->status === PaymentStatus::SUCCEEDED) {
                throw ValidationException::withMessages([
                    'order_id' => ['Order already has a succeeded payment.'],
                ]);
            }

            if ($payment && $payment->amount !== $order->total_amount) {
                throw ValidationException::withMessages([
                    'amount' => ['Payment amount must match order total.'],
                ]);
            }

            if (!$payment) {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'merchant_id' => $merchant->id,
                    'status' => PaymentStatus::PENDING,
                    'amount' => $order->total_amount,
                    'provider' => 'mock',
                ]);

                event(new PaymentCreated($payment->id, $order->id, $merchant->id));
            }

            $payment->update([
                'status' => PaymentStatus::SUCCEEDED,
            ]);

            $order->update([
                'status' => OrderStatus::PAID,
            ]);

            $payment = $payment->refresh()->load('order');

            event(new PaymentSucceeded($payment->id, $order->id, $merchant->id));
            event(new OrderPaid($order->id, $merchant->id));

            return $payment;
        });
    }
}

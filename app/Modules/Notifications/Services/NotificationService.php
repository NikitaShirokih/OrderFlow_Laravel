<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Services;

use App\Models\User;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Services\ActiveMerchantContext;
use App\Modules\Notifications\Mail\OrderCreatedMail;
use App\Modules\Notifications\Mail\OrderPaidMail;
use App\Modules\Notifications\Mail\PaymentSucceededMail;
use App\Modules\Notifications\Models\NotificationLog;
use App\Modules\Orders\Models\Order;
use App\Modules\Payments\Models\Payment;
use Throwable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class NotificationService
{
    public function send(string $type, array $data): void
    {
        $merchant = app(ActiveMerchantContext::class)->get();
        $data = $this->enrichData($type, $merchant->id, $data);
        $email = $this->resolveEmail($merchant->id, $data);

        $log = NotificationLog::create([
            'merchant_id' => $merchant->id,
            'type' => $type,
            'email' => $email,
            'status' => 'pending',
            'payload' => $data,
        ]);

        try {
            Mail::to($email)->send($this->makeMail($type, $data));

            $log->update([
                'status' => 'sent',
            ]);
        } catch (Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'payload' => $data + [
                    'error' => $exception->getMessage(),
                ],
            ]);

            throw $exception;
        }
    }

    private function makeMail(string $type, array $data): Mailable
    {
        return match ($type) {
            'order_created' => new OrderCreatedMail($data),
            'order_paid' => new OrderPaidMail($data),
            'payment_succeeded' => new PaymentSucceededMail($data),
            default => throw new InvalidArgumentException("Unsupported notification type [{$type}]."),
        };
    }

    private function enrichData(string $type, int $merchantId, array $data): array
    {
        $data['merchant_id'] = $merchantId;

        if (isset($data['order_id'])) {
            $order = Order::forMerchant($merchantId)->find($data['order_id']);

            if ($order) {
                $data['order_id'] = $order->id;
                $data['total_amount'] = $order->total_amount;
                $data['user_id'] = $order->user_id;
            }
        }

        if ($type === 'payment_succeeded' && isset($data['payment_id'])) {
            $payment = Payment::forMerchant($merchantId)->find($data['payment_id']);

            if ($payment) {
                $data['payment_id'] = $payment->id;
                $data['order_id'] = $payment->order_id;
                $data['amount'] = $payment->amount;
                $data['provider'] = $payment->provider;
            }
        }

        return $data;
    }

    private function resolveEmail(int $merchantId, array $data): string
    {
        if (!empty($data['email'])) {
            return (string) $data['email'];
        }

        if (!empty($data['user_id'])) {
            $user = User::find($data['user_id']);

            if ($user?->email) {
                return $user->email;
            }
        }

        $merchant = Merchant::find($merchantId);

        if ($merchant?->owner_id) {
            $owner = User::find($merchant->owner_id);

            if ($owner?->email) {
                return $owner->email;
            }
        }

        return config('mail.from.address');
    }
}

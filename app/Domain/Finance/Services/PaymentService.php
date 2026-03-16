<?php

namespace App\Domain\Finance\Services;

use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Models\Refund;

class PaymentService
{
    /**
     * Create a pending payment for a reservation.
     *
     * @param  array{reservation_id: int, customer_id: int|null, amount: float, method?: string}  $data
     */
    public function createPendingPayment(array $data): Payment
    {
        return Payment::create([
            'reservation_id' => $data['reservation_id'],
            'customer_id' => $data['customer_id'] ?? null,
            'payment_number' => $this->generatePaymentNumber(),
            'amount' => $data['amount'],
            'status' => PaymentStatus::Pending,
            'method' => $data['method'] ?? config('finance.default_payment_method', 'cash'),
        ]);
    }

    /**
     * Mark a payment as paid.
     */
    public function markAsPaid(Payment $payment, ?string $transactionId = null): Payment
    {
        $payment->update([
            'status' => PaymentStatus::Paid,
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);

        return $payment;
    }

    /**
     * Issue a refund for a payment.
     */
    public function refund(Payment $payment, float $amount, ?string $reason = null): Refund
    {
        $refund = $payment->refunds()->create([
            'amount' => $amount,
            'reason' => $reason,
            'refund_number' => $this->generateRefundNumber(),
            'refunded_at' => now(),
        ]);

        $totalRefunded = $payment->refunds()->sum('amount');

        if ($totalRefunded >= (float) $payment->amount) {
            $payment->update(['status' => PaymentStatus::Refunded]);
        }

        return $refund;
    }

    private function generatePaymentNumber(): string
    {
        return 'PAY-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateRefundNumber(): string
    {
        return 'REF-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}

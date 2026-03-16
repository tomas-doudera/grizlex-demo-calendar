<?php

namespace App\Domain\Finance\Listeners;

use App\Domain\Booking\Events\ReservationCreated;
use App\Domain\Finance\Services\PaymentService;

class CreatePendingPayment
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function handle(ReservationCreated $event): void
    {
        if (! config('finance.auto_create_payment_on_booking', true)) {
            return;
        }

        if ($event->amount <= 0) {
            return;
        }

        $this->paymentService->createPendingPayment([
            'reservation_id' => $event->reservationId,
            'customer_id' => $event->customerId,
            'amount' => $event->amount,
        ]);
    }
}

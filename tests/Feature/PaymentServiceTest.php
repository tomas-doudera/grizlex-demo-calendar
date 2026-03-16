<?php

use App\Domain\Booking\Events\ReservationCreated;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Models\Refund;
use App\Domain\Finance\Services\PaymentService;
use App\Domain\Shared\Enums\ReservationType;

beforeEach(function () {
    $this->service = app(PaymentService::class);
});

test('can create a pending payment', function () {
    $reservation = Reservation::factory()->create(['total_price' => 100]);

    $payment = $this->service->createPendingPayment([
        'reservation_id' => $reservation->id,
        'customer_id' => $reservation->customer_id,
        'amount' => 100.00,
    ]);

    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment->status)->toBe(PaymentStatus::Pending);
    expect((float) $payment->amount)->toBe(100.00);
    expect($payment->payment_number)->toStartWith('PAY-');
});

test('can mark payment as paid', function () {
    $payment = Payment::factory()->create(['status' => PaymentStatus::Pending]);

    $this->service->markAsPaid($payment, 'txn_12345');

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Paid);
    expect($payment->transaction_id)->toBe('txn_12345');
    expect($payment->paid_at)->not->toBeNull();
});

test('can issue a refund', function () {
    $payment = Payment::factory()->create([
        'status' => PaymentStatus::Paid,
        'amount' => 100.00,
    ]);

    $refund = $this->service->refund($payment, 50.00, 'Customer requested');

    expect($refund)->toBeInstanceOf(Refund::class);
    expect((float) $refund->amount)->toBe(50.00);
    expect($refund->reason)->toBe('Customer requested');
    expect($refund->refund_number)->toStartWith('REF-');

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Paid); // Partial, not fully refunded
});

test('full refund updates payment status to refunded', function () {
    $payment = Payment::factory()->create([
        'status' => PaymentStatus::Paid,
        'amount' => 100.00,
    ]);

    $this->service->refund($payment, 100.00, 'Full refund');

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Refunded);
});

test('listener creates pending payment on reservation created event', function () {
    $reservation = Reservation::factory()->create(['total_price' => 150]);

    event(new ReservationCreated(
        reservationId: $reservation->id,
        type: ReservationType::Individual->value,
        customerId: $reservation->customer_id,
        companyId: $reservation->company_id,
        amount: 150.00,
    ));

    $payment = Payment::query()->where('reservation_id', $reservation->id)->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(PaymentStatus::Pending);
    expect((float) $payment->amount)->toBe(150.00);
});

test('listener skips payment creation when amount is zero', function () {
    $reservation = Reservation::factory()->create(['total_price' => 0]);

    event(new ReservationCreated(
        reservationId: $reservation->id,
        type: ReservationType::Individual->value,
        customerId: $reservation->customer_id,
        companyId: $reservation->company_id,
        amount: 0,
    ));

    expect(Payment::query()->where('reservation_id', $reservation->id)->exists())->toBeFalse();
});

test('listener respects auto create config', function () {
    config(['finance.auto_create_payment_on_booking' => false]);

    $reservation = Reservation::factory()->create(['total_price' => 100]);

    event(new ReservationCreated(
        reservationId: $reservation->id,
        type: ReservationType::Individual->value,
        customerId: $reservation->customer_id,
        companyId: $reservation->company_id,
        amount: 100.00,
    ));

    expect(Payment::query()->where('reservation_id', $reservation->id)->exists())->toBeFalse();
});

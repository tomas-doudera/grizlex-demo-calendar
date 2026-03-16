<?php

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Enums\PaymentMethod;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Services\StripeCheckoutService;

beforeEach(function () {
    config(['stripe.secret' => 'sk_test_fake']);
    config(['stripe.webhook_secret' => 'whsec_test_fake']);
});

test('webhook endpoint rejects requests without valid signature', function () {
    $response = $this->postJson(route('stripe.webhook'), [], [
        'Stripe-Signature' => 'invalid',
    ]);

    $response->assertStatus(400);
});

test('stripe checkout service marks payment as paid on session completed', function () {
    $reservation = Reservation::factory()->create([
        'status' => ReservationStatus::Pending,
        'total_price' => 100.00,
    ]);

    $payment = Payment::factory()->create([
        'reservation_id' => $reservation->id,
        'status' => PaymentStatus::Pending,
        'method' => PaymentMethod::Stripe,
        'stripe_session_id' => 'cs_test_123',
        'amount' => 100.00,
    ]);

    $service = app(StripeCheckoutService::class);
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('handleSessionCompleted');

    $sessionObject = (object) [
        'id' => 'cs_test_123',
        'payment_intent' => 'pi_test_456',
    ];

    $method->invoke($service, $sessionObject);

    $payment->refresh();
    $reservation->refresh();

    expect($payment->status)->toBe(PaymentStatus::Paid);
    expect($payment->transaction_id)->toBe('pi_test_456');
    expect($reservation->status)->toBe(ReservationStatus::Confirmed);
});

test('stripe checkout service marks payment as failed on session expired', function () {
    $reservation = Reservation::factory()->create([
        'status' => ReservationStatus::Pending,
    ]);

    $payment = Payment::factory()->create([
        'reservation_id' => $reservation->id,
        'status' => PaymentStatus::Pending,
        'method' => PaymentMethod::Stripe,
        'stripe_session_id' => 'cs_test_expired',
    ]);

    $service = app(StripeCheckoutService::class);
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('handleSessionExpired');

    $sessionObject = (object) [
        'id' => 'cs_test_expired',
    ];

    $method->invoke($service, $sessionObject);

    $payment->refresh();

    expect($payment->status)->toBe(PaymentStatus::Failed);
});

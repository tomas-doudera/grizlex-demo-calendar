<?php

namespace App\Domain\Finance\Services;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Enums\PaymentMethod;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeCheckoutService
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function createCheckoutSession(
        Reservation $reservation,
        string $successUrl,
        string $cancelUrl,
    ): Session {
        $session = $this->stripe()->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'czk',
                        'product_data' => [
                            'name' => $reservation->service?->name ?? 'Reservation',
                        ],
                        'unit_amount' => (int) ($reservation->total_price * 100),
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'reservation_id' => $reservation->id,
            ],
        ]);

        $payment = $this->paymentService->createPendingPayment([
            'reservation_id' => $reservation->id,
            'customer_id' => $reservation->customer_id,
            'amount' => $reservation->total_price,
            'method' => PaymentMethod::Stripe,
        ]);

        $payment->update(['stripe_session_id' => $session->id]);

        return $session;
    }

    /**
     * @throws SignatureVerificationException
     */
    public function handleWebhook(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('stripe.webhook_secret'),
        );

        match ($event->type) {
            'checkout.session.completed' => $this->handleSessionCompleted($event->data->object),
            'checkout.session.expired' => $this->handleSessionExpired($event->data->object),
            default => null,
        };
    }

    private function handleSessionCompleted(object $session): void
    {
        $payment = Payment::where('stripe_session_id', $session->id)->first();

        if (! $payment) {
            return;
        }

        $this->paymentService->markAsPaid($payment, $session->payment_intent);

        $payment->reservation?->update([
            'status' => ReservationStatus::Confirmed,
        ]);
    }

    private function handleSessionExpired(object $session): void
    {
        $payment = Payment::where('stripe_session_id', $session->id)->first();

        if (! $payment) {
            return;
        }

        $payment->update(['status' => PaymentStatus::Failed]);
    }

    private function stripe(): StripeClient
    {
        return new StripeClient(config('stripe.secret'));
    }
}

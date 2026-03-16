<?php

namespace App\Livewire;

use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class BookingSuccess extends Component
{
    #[Url]
    public ?string $session_id = null;

    #[Url]
    public ?int $reservation_id = null;

    public ?Reservation $reservation = null;

    public bool $paymentVerified = false;

    public function mount(): void
    {
        if ($this->session_id) {
            $payment = Payment::where('stripe_session_id', $this->session_id)->first();

            if ($payment) {
                $this->reservation = $payment->reservation;
                $this->paymentVerified = $payment->status === PaymentStatus::Paid;
            }
        } elseif ($this->reservation_id) {
            $this->reservation = Reservation::find($this->reservation_id);
            $this->paymentVerified = true;
        }

        $this->reservation?->loadMissing(['service', 'place', 'company']);
    }

    public function render(): View
    {
        return view('livewire.booking-success')
            ->layout('layouts.booking', ['title' => __('booking.success.title')]);
    }
}

<?php

namespace App\Livewire;

use App\Domain\Booking\Models\Reservation;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class BookingCancel extends Component
{
    #[Url]
    public ?int $reservation_id = null;

    public ?Reservation $reservation = null;

    public function mount(): void
    {
        if ($this->reservation_id) {
            $this->reservation = Reservation::with('service')->find($this->reservation_id);
        }
    }

    public function render(): View
    {
        return view('livewire.booking-cancel')
            ->layout('layouts.booking', ['title' => __('booking.cancel.title')]);
    }
}

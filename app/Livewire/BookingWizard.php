<?php

namespace App\Livewire;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Events\ReservationCreated;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Booking\Services\AvailabilityCalculator;
use App\Domain\Finance\Services\StripeCheckoutService;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\Customer;
use App\Domain\Shared\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class BookingWizard extends Component
{
    public int $step = 1;

    public ?int $companyId = null;

    public ?int $serviceId = null;

    public ?int $placeId = null;

    public ?string $selectedDate = null;

    public ?string $selectedSlotFrom = null;

    public ?string $selectedSlotTo = null;

    public string $guestName = '';

    public string $guestEmail = '';

    public string $guestPhone = '';

    public string $notes = '';

    /** @var array<int, array{from: string, to: string, label: string}> */
    public array $availableSlots = [];

    public function mount(): void
    {
        $company = Company::where('is_active', true)->first();

        if ($company) {
            $this->companyId = $company->id;
        }

        if (auth()->check()) {
            $this->guestName = auth()->user()->name;
            $this->guestEmail = auth()->user()->email;
        }
    }

    public function getCompaniesProperty(): Collection
    {
        return Company::where('is_active', true)->get();
    }

    public function getServicesProperty(): Collection
    {
        if (! $this->companyId) {
            return collect();
        }

        return Service::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getPlacesProperty(): Collection
    {
        if (! $this->companyId) {
            return collect();
        }

        return Place::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getSelectedServiceProperty(): ?Service
    {
        return $this->serviceId ? Service::find($this->serviceId) : null;
    }

    public function selectService(int $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    public function selectPlace(int $placeId): void
    {
        $this->placeId = $placeId;
        $this->availableSlots = [];
        $this->selectedSlotFrom = null;
        $this->selectedSlotTo = null;
    }

    public function updatedSelectedDate(): void
    {
        $this->loadAvailableSlots();
    }

    public function updatedSelectedSlotFrom(?string $value): void
    {
        if ($value === null) {
            $this->selectedSlotTo = null;

            return;
        }

        $slot = collect($this->availableSlots)->firstWhere('from', $value);
        $this->selectedSlotTo = $slot['to'] ?? null;
    }

    public function selectSlot(string $from, string $to): void
    {
        $this->selectedSlotFrom = $from;
        $this->selectedSlotTo = $to;
    }

    public function loadAvailableSlots(): void
    {
        $this->selectedSlotFrom = null;
        $this->selectedSlotTo = null;

        if (! $this->placeId || ! $this->serviceId || ! $this->selectedDate) {
            $this->availableSlots = [];

            return;
        }

        $service = Service::find($this->serviceId);
        $company = Company::find($this->companyId);
        $date = Carbon::parse($this->selectedDate);

        if (! $service || ! $company || ($date->isPast() && ! $date->isToday())) {
            $this->availableSlots = [];

            return;
        }

        $calculator = app(AvailabilityCalculator::class);

        $dayStart = $company->opening_time
            ? $date->copy()->setTimeFrom($company->opening_time)
            : null;
        $dayEnd = $company->closing_time
            ? $date->copy()->setTimeFrom($company->closing_time)
            : null;

        $slots = $calculator->getAvailableSlots(
            date: $date,
            durationMinutes: $service->duration_minutes,
            intervalMinutes: 30,
            constraints: ['place_id' => $this->placeId],
            dayStart: $dayStart,
            dayEnd: $dayEnd,
        );

        $this->availableSlots = collect($slots)->map(fn (array $slot) => [
            'from' => $slot['from']->format('Y-m-d H:i:s'),
            'to' => $slot['to']->format('Y-m-d H:i:s'),
            'label' => $slot['from']->format('H:i').' - '.$slot['to']->format('H:i'),
        ])->values()->all();
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->step) {
            $this->step = $step;

            return;
        }

        if ($step === 2 && $this->validateStep1()) {
            $this->step = 2;
        } elseif ($step === 3 && $this->validateStep2()) {
            $this->step = 3;
        } elseif ($step === 4 && $this->validateStep3()) {
            $this->step = 4;
        }
    }

    public function nextStep(): void
    {
        $this->goToStep($this->step + 1);
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    protected function validateStep1(): bool
    {
        if (! $this->companyId || ! $this->serviceId) {
            $this->addError('step1', __('booking.errors.select_service'));

            return false;
        }

        return true;
    }

    protected function validateStep2(): bool
    {
        if (! $this->placeId || ! $this->selectedSlotFrom || ! $this->selectedSlotTo) {
            $this->addError('step2', __('booking.errors.select_slot'));

            return false;
        }

        $calculator = app(AvailabilityCalculator::class);

        if (! $calculator->isAvailable(
            Carbon::parse($this->selectedSlotFrom),
            Carbon::parse($this->selectedSlotTo),
            ['place_id' => $this->placeId],
        )) {
            $this->addError('step2', __('booking.errors.slot_unavailable'));

            return false;
        }

        return true;
    }

    protected function validateStep3(): bool
    {
        $this->validate([
            'guestName' => ['required', 'string', 'max:255'],
            'guestEmail' => ['required', 'email', 'max:255'],
            'guestPhone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        return true;
    }

    public function confirmBooking(): mixed
    {
        if (! $this->validateStep3()) {
            return null;
        }

        $service = Service::findOrFail($this->serviceId);

        $nameParts = explode(' ', $this->guestName, 2);
        $customer = Customer::firstOrCreate(
            ['email' => $this->guestEmail],
            [
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'phone' => $this->guestPhone ?: null,
            ],
        );

        $reservation = Reservation::create([
            'company_id' => $this->companyId,
            'place_id' => $this->placeId,
            'service_id' => $this->serviceId,
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'from_time' => $this->selectedSlotFrom,
            'to_time' => $this->selectedSlotTo,
            'status' => $service->requires_payment ? ReservationStatus::Pending : ReservationStatus::Confirmed,
            'total_price' => $service->price,
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone' => $this->guestPhone ?: null,
            'notes' => $this->notes ?: null,
        ]);

        event(new ReservationCreated(
            reservationId: $reservation->id,
            type: $reservation->type ?? 'individual',
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
            amount: (float) $reservation->total_price,
        ));

        if ($service->requires_payment) {
            $stripeService = app(StripeCheckoutService::class);
            $session = $stripeService->createCheckoutSession(
                $reservation,
                route('booking.success'),
                route('booking.cancel'),
            );

            return $this->redirect($session->url);
        }

        return $this->redirect(route('booking.success', ['reservation_id' => $reservation->id]));
    }

    public function render(): View
    {
        return view('livewire.booking-wizard')
            ->layout('layouts.booking', ['title' => __('booking.title')]);
    }
}

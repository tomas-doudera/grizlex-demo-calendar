<?php

use App\Domain\Booking\Models\Reservation;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\Service;
use App\Domain\Shared\Models\User;
use App\Livewire\BookingWizard;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->company = Company::factory()->create([
        'opening_time' => '08:00',
        'closing_time' => '18:00',
    ]);

    $this->place = Place::factory()->create([
        'company_id' => $this->company->id,
    ]);

    $this->service = Service::factory()->create([
        'company_id' => $this->company->id,
        'duration_minutes' => 60,
        'price' => 50.00,
        'requires_payment' => false,
    ]);
});

test('booking page loads successfully', function () {
    $this->get(route('booking'))->assertOk();
});

test('wizard starts at step 1', function () {
    Livewire::test(BookingWizard::class)
        ->assertSet('step', 1);
});

test('auto selects first company', function () {
    Livewire::test(BookingWizard::class)
        ->assertSet('companyId', $this->company->id);
});

test('can select a service', function () {
    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->assertSet('serviceId', $this->service->id);
});

test('can navigate to step 2 after selecting service', function () {
    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->call('nextStep')
        ->assertSet('step', 2);
});

test('cannot navigate to step 2 without selecting service', function () {
    Livewire::test(BookingWizard::class)
        ->call('nextStep')
        ->assertSet('step', 1)
        ->assertHasErrors('step1');
});

test('can select place and load available slots', function () {
    $date = Carbon::tomorrow()->format('Y-m-d');

    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->call('nextStep')
        ->call('selectPlace', $this->place->id)
        ->set('selectedDate', $date)
        ->assertNotSet('availableSlots', []);
});

test('can select a time slot', function () {
    $from = Carbon::tomorrow()->setTime(9, 0)->format('Y-m-d H:i:s');
    $to = Carbon::tomorrow()->setTime(10, 0)->format('Y-m-d H:i:s');

    Livewire::test(BookingWizard::class)
        ->call('selectSlot', $from, $to)
        ->assertSet('selectedSlotFrom', $from)
        ->assertSet('selectedSlotTo', $to);
});

test('can navigate through all steps and confirm booking without payment', function () {
    $date = Carbon::tomorrow();

    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->call('nextStep')
        ->call('selectPlace', $this->place->id)
        ->set('selectedDate', $date->format('Y-m-d'))
        ->call('selectSlot',
            $date->copy()->setTime(9, 0)->format('Y-m-d H:i:s'),
            $date->copy()->setTime(10, 0)->format('Y-m-d H:i:s'),
        )
        ->call('nextStep')
        ->set('guestName', 'John Doe')
        ->set('guestEmail', 'john@example.com')
        ->set('guestPhone', '123456789')
        ->call('nextStep')
        ->assertSet('step', 4)
        ->call('confirmBooking')
        ->assertRedirect(route('booking.success', ['reservation_id' => Reservation::latest()->first()->id]));

    $this->assertDatabaseHas('reservations', [
        'guest_name' => 'John Doe',
        'guest_email' => 'john@example.com',
        'service_id' => $this->service->id,
        'status' => 'confirmed',
    ]);
});

test('pre-fills guest info for authenticated users', function () {
    $user = User::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(BookingWizard::class)
        ->assertSet('guestName', 'Jane Smith')
        ->assertSet('guestEmail', 'jane@example.com');
});

test('validates guest information on step 3', function () {
    $date = Carbon::tomorrow();

    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->call('nextStep')
        ->call('selectPlace', $this->place->id)
        ->set('selectedDate', $date->format('Y-m-d'))
        ->call('selectSlot',
            $date->copy()->setTime(9, 0)->format('Y-m-d H:i:s'),
            $date->copy()->setTime(10, 0)->format('Y-m-d H:i:s'),
        )
        ->call('nextStep')
        ->set('guestName', '')
        ->set('guestEmail', 'invalid-email')
        ->call('nextStep')
        ->assertHasErrors(['guestName', 'guestEmail']);
});

test('can go back to previous steps', function () {
    Livewire::test(BookingWizard::class)
        ->call('selectService', $this->service->id)
        ->call('nextStep')
        ->assertSet('step', 2)
        ->call('previousStep')
        ->assertSet('step', 1);
});

test('success page loads', function () {
    $this->get(route('booking.success'))->assertOk();
});

test('cancel page loads', function () {
    $this->get(route('booking.cancel'))->assertOk();
});

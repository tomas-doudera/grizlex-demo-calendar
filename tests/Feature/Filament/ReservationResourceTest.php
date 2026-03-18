<?php

use App\Filament\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Resources\Reservations\Pages\EditReservation;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListReservations::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreateReservation::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $reservation = Reservation::factory()->create();

    Livewire::test(EditReservation::class, ['record' => $reservation->getRouteKey()])
        ->assertSuccessful();
});

it('can list reservations', function () {
    $reservations = Reservation::factory()->count(3)->create();

    Livewire::test(ListReservations::class)
        ->assertCanSeeTableRecords($reservations);
});

it('can create a reservation', function () {
    $company = Company::factory()->create();
    $place = Place::factory()->create(['company_id' => $company->id]);

    Livewire::test(CreateReservation::class)
        ->fillForm([
            'company_id' => $company->id,
            'place_id' => $place->id,
            'from_time' => now()->addDay()->setHour(10)->setMinute(0),
            'to_time' => now()->addDay()->setHour(11)->setMinute(0),
            'status' => 'pending',
            'guest_name' => 'John Doe',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Reservation::query()->where('guest_name', 'John Doe')->exists())->toBeTrue();
});

it('can update a reservation', function () {
    $reservation = Reservation::factory()->create();

    Livewire::test(EditReservation::class, ['record' => $reservation->getRouteKey()])
        ->fillForm([
            'guest_name' => 'Jane Doe',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($reservation->fresh()->guest_name)->toBe('Jane Doe');
});

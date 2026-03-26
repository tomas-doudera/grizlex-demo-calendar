<?php

use App\Filament\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Resources\Reservations\Pages\EditReservation;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

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
    $venue = Venue::factory()->create(['place_id' => $place->id]);

    Livewire::test(CreateReservation::class)
        ->fillForm([
            'company_id' => $company->id,
            'venue_id' => $venue->id,
            'from_time' => now()->addDay()->setHour(10)->setMinute(0),
            'to_time' => now()->addDay()->setHour(11)->setMinute(0),
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Reservation::query()->exists())->toBeTrue();
});

it('syncs multiple customers when creating a reservation', function () {
    $company = Company::factory()->create();
    $place = Place::factory()->create(['company_id' => $company->id]);
    $venue = Venue::factory()->create(['place_id' => $place->id]);
    $customers = Customer::factory()->count(2)->create();

    Livewire::test(CreateReservation::class)
        ->fillForm([
            'company_id' => $company->id,
            'venue_id' => $venue->id,
            'from_time' => now()->addDay()->setHour(10)->setMinute(0),
            'to_time' => now()->addDay()->setHour(11)->setMinute(0),
            'status' => 'pending',
            'customers' => $customers->pluck('id')->all(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $reservation = Reservation::query()->latest('id')->first();

    expect($reservation)->not->toBeNull()
        ->and($reservation->customers()->count())->toBe(2);
});

it('can update a reservation', function () {
    $company = Company::factory()->create();
    $place = Place::factory()->create([
        'company_id' => $company->id,
    ]);
    $venue = Venue::factory()->create([
        'place_id' => $place->id,
        'capacity' => 30,
    ]);
    $reservation = Reservation::factory()->create([
        'company_id' => $company->id,
        'venue_id' => $venue->id,
        'staff_id' => null,
        'capacity' => 10,
        'booked_count' => 0,
    ]);

    Livewire::test(EditReservation::class, ['record' => $reservation->getRouteKey()])
        ->fillForm([
            'booked_count' => 4,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'booked_count' => 4,
    ]);
});

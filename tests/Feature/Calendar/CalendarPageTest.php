<?php

use App\Domain\Booking\Filament\Pages\Calendar;
use App\Domain\Booking\Models\Reservation;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\User;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->user = User::factory()->create();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->user);

    $this->company = Company::factory()->create(['title' => 'Test Company']);
    $this->places = collect([
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room A', 'short_title' => 'RA']),
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room B', 'short_title' => 'RB']),
    ]);
});

test('calendar page can be rendered by authenticated user', function () {
    $this->get(Calendar::getUrl())
        ->assertSuccessful();
});

test('guests cannot access the calendar page', function () {
    auth()->logout();

    $this->get(Calendar::getUrl())
        ->assertRedirect();
});

test('reservation model casts from_time and to_time to datetime', function () {
    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => '2026-03-16 10:00:00',
        'to_time' => '2026-03-16 11:00:00',
    ]);

    $reservation->refresh();

    expect($reservation->from_time)->toBeInstanceOf(CarbonImmutable::class);
    expect($reservation->to_time)->toBeInstanceOf(CarbonImmutable::class);
});

test('reservation belongs to a company', function () {
    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($reservation->company)->toBeInstanceOf(Company::class);
    expect($reservation->company->id)->toBe($this->company->id);
});

test('reservation belongs to a place', function () {
    $place = $this->places->first();
    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($reservation->place)->toBeInstanceOf(Place::class);
    expect($reservation->place->id)->toBe($place->id);
});

test('place belongs to a company', function () {
    $place = $this->places->first();

    expect($place->company)->toBeInstanceOf(Company::class);
    expect($place->company->id)->toBe($this->company->id);
});

test('place has many reservations', function () {
    $place = $this->places->first();

    Reservation::factory()->count(3)->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($place->reservations)->toHaveCount(3);
});

test('company has many places', function () {
    expect($this->company->places)->toHaveCount(2);
});

test('company has many reservations', function () {
    Reservation::factory()->count(2)->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($this->company->reservations)->toHaveCount(2);
});

test('deleting a company cascades to places and reservations', function () {
    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $this->company->delete();

    expect(Place::where('company_id', $this->company->id)->count())->toBe(0);
    expect(Reservation::where('company_id', $this->company->id)->count())->toBe(0);
});

test('deleting a place cascades to its reservations', function () {
    $place = $this->places->first();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $place->delete();

    expect(Reservation::where('place_id', $place->id)->count())->toBe(0);
});

test('reservation factory creates valid records', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation)->toBeInstanceOf(Reservation::class);
    expect($reservation->company_id)->not->toBeNull();
    expect($reservation->place_id)->not->toBeNull();
    expect($reservation->from_time)->not->toBeNull();
    expect($reservation->to_time)->not->toBeNull();
    expect($reservation->to_time->gt($reservation->from_time))->toBeTrue();
});

test('reservation can store capacity and booked count', function () {
    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
        'capacity' => 20,
        'booked_count' => 15,
    ]);

    $reservation->refresh();

    expect($reservation->capacity)->toBe(20);
    expect($reservation->booked_count)->toBe(15);
});

test('reservations can be queried by date range', function () {
    $monday = now()->startOfWeek();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => $monday->copy()->setHour(10),
        'to_time' => $monday->copy()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->places->first()->id,
        'from_time' => $monday->copy()->addWeeks(3)->setHour(10),
        'to_time' => $monday->copy()->addWeeks(3)->setHour(11),
    ]);

    $startDate = $monday->copy()->startOfDay();
    $endDate = $monday->copy()->endOfWeek()->endOfDay();

    $events = Reservation::query()
        ->where('company_id', $this->company->id)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('from_time', [$startDate, $endDate])
                ->orWhereBetween('to_time', [$startDate, $endDate]);
        })
        ->get();

    expect($events)->toHaveCount(1);
});

test('reservations can be filtered by place ids', function () {
    $placeA = $this->places->first();
    $placeB = $this->places->last();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $placeA->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $placeB->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $filtered = Reservation::query()
        ->whereIn('place_id', [$placeA->id])
        ->get();

    expect($filtered)->toHaveCount(1);
    expect($filtered->first()->place_id)->toBe($placeA->id);
});

test('overlapping reservations can be detected', function () {
    $place = $this->places->first();
    $baseTime = now()->startOfWeek()->setHour(10);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'from_time' => $baseTime->copy(),
        'to_time' => $baseTime->copy()->addHour(),
    ]);

    $newFrom = $baseTime->copy()->addMinutes(30);
    $newTo = $baseTime->copy()->addMinutes(90);

    $hasOverlap = Reservation::where('place_id', $place->id)
        ->where('from_time', '<', $newTo)
        ->where('to_time', '>', $newFrom)
        ->exists();

    expect($hasOverlap)->toBeTrue();

    // Non-overlapping time should not conflict
    $noOverlap = Reservation::where('place_id', $place->id)
        ->where('from_time', '<', $baseTime->copy()->addHours(3))
        ->where('to_time', '>', $baseTime->copy()->addHours(2))
        ->exists();

    expect($noOverlap)->toBeFalse();
});

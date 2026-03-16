<?php

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\PlaceBooking\Models\PlaceSchedule;
use App\Domain\PlaceBooking\Services\PlaceAvailabilityService;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->service = app(PlaceAvailabilityService::class);

    $this->place = Place::factory()->create();

    // Monday schedule: 08:00-22:00
    PlaceSchedule::factory()->create([
        'place_id' => $this->place->id,
        'day_of_week' => 0, // Monday
        'start_time' => '08:00:00',
        'end_time' => '22:00:00',
        'is_active' => true,
    ]);
});

test('place is available within schedule', function () {
    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00'); // Monday
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeTrue();
});

test('place is not available outside schedule hours', function () {
    $dateTime = CarbonImmutable::parse('2026-03-16 06:00:00'); // Monday before opening
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeFalse();
});

test('place is not available on unscheduled day', function () {
    $dateTime = CarbonImmutable::parse('2026-03-17 10:00:00'); // Tuesday — no schedule
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeFalse();
});

test('place is not available when conflicting reservation exists', function () {
    Reservation::factory()->create([
        'place_id' => $this->place->id,
        'from_time' => '2026-03-16 10:00:00',
        'to_time' => '2026-03-16 11:00:00',
        'status' => ReservationStatus::Confirmed,
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeFalse();
});

test('place is available when only cancelled reservations exist', function () {
    Reservation::factory()->create([
        'place_id' => $this->place->id,
        'from_time' => '2026-03-16 10:00:00',
        'to_time' => '2026-03-16 11:00:00',
        'status' => ReservationStatus::Cancelled,
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeTrue();
});

test('inactive schedule is ignored', function () {
    PlaceSchedule::query()->where('place_id', $this->place->id)->update(['is_active' => false]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->place, $dateTime, 60))->toBeFalse();
});

test('get available slots returns correct slots', function () {
    $date = CarbonImmutable::parse('2026-03-16'); // Monday
    $slots = $this->service->getAvailableSlots($this->place, $date, 60);

    expect($slots)->not->toBeEmpty();
    expect($slots->first()['start']->format('H:i'))->toBe('08:00');
    expect($slots->first()['end']->format('H:i'))->toBe('09:00');
});

test('get available slots returns empty for unscheduled day', function () {
    $date = CarbonImmutable::parse('2026-03-17'); // Tuesday
    $slots = $this->service->getAvailableSlots($this->place, $date, 60);

    expect($slots)->toBeEmpty();
});

test('place model implements HasAvailability contract', function () {
    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->place->isAvailableAt($dateTime, 60))->toBeTrue();
});

<?php

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\IndividualBooking\Models\StaffBreak;
use App\Domain\IndividualBooking\Models\StaffSchedule;
use App\Domain\IndividualBooking\Services\StaffAvailabilityService;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->service = app(StaffAvailabilityService::class);

    $this->staff = Staff::factory()->create();

    // Monday schedule: 09:00-17:00
    StaffSchedule::factory()->create([
        'staff_id' => $this->staff->id,
        'day_of_week' => 0, // Monday
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'is_active' => true,
    ]);
});

test('staff is available within schedule', function () {
    // Monday at 10:00, 60 minutes
    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00'); // Monday
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeTrue();
});

test('staff is not available outside schedule hours', function () {
    // Monday at 07:00 (before schedule starts)
    $dateTime = CarbonImmutable::parse('2026-03-16 07:00:00'); // Monday
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is not available on unscheduled day', function () {
    // Tuesday — no schedule defined
    $dateTime = CarbonImmutable::parse('2026-03-17 10:00:00'); // Tuesday
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is not available when slot exceeds schedule end', function () {
    // Monday at 16:30, 60 minutes → would end at 17:30, past 17:00
    $dateTime = CarbonImmutable::parse('2026-03-16 16:30:00');
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is not available during recurring break', function () {
    StaffBreak::factory()->create([
        'staff_id' => $this->staff->id,
        'day_of_week' => 0, // Monday
        'date' => null,
        'start_time' => '12:00:00',
        'end_time' => '13:00:00',
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 12:00:00'); // Monday
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is not available during one-off break', function () {
    StaffBreak::factory()->create([
        'staff_id' => $this->staff->id,
        'date' => '2026-03-16',
        'day_of_week' => null,
        'start_time' => '14:00:00',
        'end_time' => '15:00:00',
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 14:00:00');
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is not available when conflicting reservation exists', function () {
    Reservation::factory()->create([
        'staff_id' => $this->staff->id,
        'from_time' => '2026-03-16 10:00:00',
        'to_time' => '2026-03-16 11:00:00',
        'status' => ReservationStatus::Confirmed,
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('staff is available when only cancelled reservations exist', function () {
    Reservation::factory()->create([
        'staff_id' => $this->staff->id,
        'from_time' => '2026-03-16 10:00:00',
        'to_time' => '2026-03-16 11:00:00',
        'status' => ReservationStatus::Cancelled,
    ]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeTrue();
});

test('inactive schedule is ignored', function () {
    // Deactivate the Monday schedule
    StaffSchedule::query()->where('staff_id', $this->staff->id)->update(['is_active' => false]);

    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->service->isAvailable($this->staff, $dateTime, 60))->toBeFalse();
});

test('get available slots returns correct slots', function () {
    $date = CarbonImmutable::parse('2026-03-16'); // Monday

    // With 60-min slots and 15-min buffer, slots: 09:00, 10:15, 11:30, 12:45, 14:00, 15:15
    $slots = $this->service->getAvailableSlots($this->staff, $date, 60);

    expect($slots)->not->toBeEmpty();
    expect($slots->first()['start']->format('H:i'))->toBe('09:00');
    expect($slots->first()['end']->format('H:i'))->toBe('10:00');
});

test('get available slots excludes break times', function () {
    StaffBreak::factory()->create([
        'staff_id' => $this->staff->id,
        'day_of_week' => 0,
        'date' => null,
        'start_time' => '12:00:00',
        'end_time' => '13:00:00',
    ]);

    $date = CarbonImmutable::parse('2026-03-16'); // Monday
    $slots = $this->service->getAvailableSlots($this->staff, $date, 60);

    // No slot should start during the break
    $breakSlots = $slots->filter(fn (array $slot) => $slot['start']->format('H:i') === '12:00');

    expect($breakSlots)->toBeEmpty();
});

test('get available slots returns empty for unscheduled day', function () {
    $date = CarbonImmutable::parse('2026-03-17'); // Tuesday — no schedule
    $slots = $this->service->getAvailableSlots($this->staff, $date, 60);

    expect($slots)->toBeEmpty();
});

test('staff model implements HasAvailability contract', function () {
    $dateTime = CarbonImmutable::parse('2026-03-16 10:00:00');
    expect($this->staff->isAvailableAt($dateTime, 60))->toBeTrue();
});

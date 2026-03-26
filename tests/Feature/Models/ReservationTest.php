<?php

use App\Enums\ReservationStatus;
use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Carbon\CarbonImmutable;

it('can be created with factory', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation)->toBeInstanceOf(Reservation::class)
        ->and($reservation->status)->toBe(ReservationStatus::Pending);
});

it('belongs to a company', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation->company)->toBeInstanceOf(Company::class);
});

it('belongs to a venue', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation->venue)->toBeInstanceOf(Venue::class);
});

it('can belong to a user', function () {
    $reservation = Reservation::factory()->forUser()->create();

    expect($reservation->user)->toBeInstanceOf(User::class)
        ->and($reservation->getAttributes()['guest_name'] ?? null)->toBeNull();
});

it('casts from_time and to_time as immutable datetime', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation->from_time)->toBeInstanceOf(CarbonImmutable::class)
        ->and($reservation->to_time)->toBeInstanceOf(CarbonImmutable::class);
});

it('casts status to ReservationStatus enum', function () {
    $reservation = Reservation::factory()->create();

    expect($reservation->status)->toBeInstanceOf(ReservationStatus::class);
});

it('can be created as confirmed', function () {
    $reservation = Reservation::factory()->confirmed()->create();

    expect($reservation->status)->toBe(ReservationStatus::Confirmed)
        ->and($reservation->confirmed_at)->not->toBeNull();
});

it('can be created as cancelled', function () {
    $reservation = Reservation::factory()->cancelled()->create();

    expect($reservation->status)->toBe(ReservationStatus::Cancelled)
        ->and($reservation->cancelled_at)->not->toBeNull()
        ->and($reservation->cancellation_reason)->not->toBeEmpty();
});

it('uses soft deletes', function () {
    $reservation = Reservation::factory()->create();
    $reservation->delete();

    expect(Reservation::withTrashed()->find($reservation->id))->not->toBeNull()
        ->and(Reservation::find($reservation->id))->toBeNull();
});

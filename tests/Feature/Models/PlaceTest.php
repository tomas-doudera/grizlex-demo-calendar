<?php

use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;

it('can be created with factory', function () {
    $place = Place::factory()->create();

    expect($place)->toBeInstanceOf(Place::class)
        ->and($place->title)->not->toBeEmpty()
        ->and($place->is_active)->toBeTrue();
});

it('belongs to a company', function () {
    $company = Company::factory()->create();
    $place = Place::factory()->create(['company_id' => $company->id]);

    expect($place->company->id)->toBe($company->id);
});

it('has reservations relationship', function () {
    $place = Place::factory()->create();
    Reservation::factory()->count(2)->create([
        'company_id' => $place->company_id,
        'place_id' => $place->id,
    ]);

    expect($place->reservations)->toHaveCount(2);
});

it('has default booking constraints', function () {
    $place = Place::factory()->create();

    expect($place->min_booking_minutes)->toBe(30)
        ->and($place->max_booking_minutes)->toBe(120)
        ->and($place->booking_interval_minutes)->toBe(15)
        ->and($place->advance_booking_days)->toBe(30)
        ->and($place->cancellation_hours)->toBe(24);
});

it('can be created as inactive', function () {
    $place = Place::factory()->inactive()->create();

    expect($place->is_active)->toBeFalse();
});

it('casts integer fields correctly', function () {
    $place = Place::factory()->create(['capacity' => '10']);

    expect($place->capacity)->toBeInt()->toBe(10);
});

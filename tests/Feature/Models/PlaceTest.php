<?php

use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Venue;

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

it('has reservations relationship through venues', function () {
    $place = Place::factory()->create();
    $venue = Venue::factory()->create(['place_id' => $place->id]);
    Reservation::factory()->count(2)->create([
        'company_id' => $place->company_id,
        'venue_id' => $venue->id,
    ]);

    expect($place->reservations)->toHaveCount(2);
});

it('can be created as inactive', function () {
    $place = Place::factory()->inactive()->create();

    expect($place->is_active)->toBeFalse();
});

it('merges opening hours for calendar week across places', function () {
    $placeA = Place::factory()->make([
        'opening_hours' => [
            'monday' => '09:00-12:00',
            'tuesday' => '',
        ],
    ]);
    $placeB = Place::factory()->make([
        'opening_hours' => [
            'monday' => '14:00-18:00',
            'tuesday' => '10:00-16:00',
        ],
    ]);

    $merged = Place::mergeOpeningHoursForCalendarWeek([$placeA, $placeB]);

    expect($merged['Monday']['min'])->toBe(9)
        ->and($merged['Monday']['max'])->toBe(18)
        ->and($merged['Tuesday']['min'])->toBe(10)
        ->and($merged['Tuesday']['max'])->toBe(16);
});

<?php

use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Venue;

it('can be created with factory', function () {
    $company = Company::factory()->create();

    expect($company)->toBeInstanceOf(Company::class)
        ->and($company->title)->not->toBeEmpty()
        ->and($company->is_active)->toBeTrue();
});

it('casts is_active to boolean', function () {
    $company = Company::factory()->create(['is_active' => 1]);

    expect($company->is_active)->toBeBool()->toBeTrue();
});

it('has places relationship', function () {
    $company = Company::factory()->create();
    Place::factory()->count(3)->create(['company_id' => $company->id]);

    expect($company->places)->toHaveCount(3);
});

it('has reservations relationship', function () {
    $company = Company::factory()->create();
    $place = Place::factory()->create(['company_id' => $company->id]);
    $venue = Venue::factory()->create(['place_id' => $place->id]);
    Reservation::factory()->count(2)->create([
        'company_id' => $company->id,
        'venue_id' => $venue->id,
    ]);

    expect($company->reservations)->toHaveCount(2);
});

it('can be created as inactive', function () {
    $company = Company::factory()->inactive()->create();

    expect($company->is_active)->toBeFalse();
});

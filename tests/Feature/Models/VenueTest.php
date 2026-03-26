<?php

use App\Models\Place;
use App\Models\Venue;

it('can be created with factory', function () {
    $venue = Venue::factory()->create();

    expect($venue)->toBeInstanceOf(Venue::class)
        ->and($venue->title)->not->toBeEmpty();
});

it('belongs to a place', function () {
    $place = Place::factory()->create();
    $venue = Venue::factory()->create(['place_id' => $place->id]);

    expect($venue->place->id)->toBe($place->id);
});

it('casts capacity to integer', function () {
    $venue = Venue::factory()->create(['capacity' => '10']);

    expect($venue->capacity)->toBeInt()->toBe(10);
});

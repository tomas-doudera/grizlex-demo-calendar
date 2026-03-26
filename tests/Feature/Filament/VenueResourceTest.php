<?php

use App\Filament\Resources\Venues\Pages\CreateVenue;
use App\Filament\Resources\Venues\Pages\EditVenue;
use App\Filament\Resources\Venues\Pages\ListVenues;
use App\Models\User;
use App\Models\Venue;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListVenues::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreateVenue::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $venue = Venue::factory()->create();

    Livewire::test(EditVenue::class, ['record' => $venue->getRouteKey()])
        ->assertSuccessful();
});

it('can list venues', function () {
    $venues = Venue::factory()->count(3)->create();

    Livewire::test(ListVenues::class)
        ->assertCanSeeTableRecords($venues);
});

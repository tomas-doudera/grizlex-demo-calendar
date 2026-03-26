<?php

use App\Filament\Resources\Places\Pages\CreatePlace;
use App\Filament\Resources\Places\Pages\EditPlace;
use App\Filament\Resources\Places\Pages\ListPlaces;
use App\Models\Company;
use App\Models\Place;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListPlaces::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreatePlace::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $place = Place::factory()->create();

    Livewire::test(EditPlace::class, ['record' => $place->getRouteKey()])
        ->assertSuccessful();
});

it('can list places', function () {
    $places = Place::factory()->count(3)->create();

    Livewire::test(ListPlaces::class)
        ->assertCanSeeTableRecords($places);
});

it('can create a place', function () {
    $company = Company::factory()->create();

    Livewire::test(CreatePlace::class)
        ->fillForm([
            'title' => 'Test Branch',
            'company_id' => $company->id,
            'city' => 'Prague',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Place::query()->where('title', 'Test Branch')->exists())->toBeTrue();
});

it('can update a place', function () {
    $place = Place::factory()->create(['phone' => null]);

    Livewire::test(EditPlace::class, ['record' => $place->getRouteKey()])
        ->fillForm([
            'title' => 'Updated Branch',
            'opening_hours' => [
                'monday' => '09:00-12:00, 13:00-17:00',
                'tuesday' => '',
                'wednesday' => '',
                'thursday' => '',
                'friday' => '',
                'saturday' => '',
                'sunday' => '',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $place->refresh();

    expect($place->title)->toBe('Updated Branch')
        ->and($place->opening_hours['monday'])->toBe('09:00-12:00, 13:00-17:00');
});

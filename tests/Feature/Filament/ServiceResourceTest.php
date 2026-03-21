<?php

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListServices::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreateService::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $service = Service::factory()->create();

    Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
        ->assertSuccessful();
});

it('can list services', function () {
    $services = Service::factory()->count(3)->create();

    Livewire::test(ListServices::class)
        ->assertCanSeeTableRecords($services);
});

it('can create a service', function () {
    $company = Company::factory()->create();

    Livewire::test(CreateService::class)
        ->fillForm([
            'title' => 'Test Service',
            'company_id' => $company->id,
            'duration_minutes' => 60,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Service::query()->where('title', 'Test Service')->exists())->toBeTrue();
});

it('can update a service', function () {
    $service = Service::factory()->create();

    Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
        ->fillForm([
            'title' => 'Updated Service',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($service->fresh()->title)->toBe('Updated Service');
});

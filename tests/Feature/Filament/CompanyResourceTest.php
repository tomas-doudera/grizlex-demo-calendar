<?php

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListCompanies::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreateCompany::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $company = Company::factory()->create();

    Livewire::test(EditCompany::class, ['record' => $company->getRouteKey()])
        ->assertSuccessful();
});

it('can list companies', function () {
    $companies = Company::factory()->count(3)->create();

    Livewire::test(ListCompanies::class)
        ->assertCanSeeTableRecords($companies);
});

it('can create a company', function () {
    Livewire::test(CreateCompany::class)
        ->fillForm([
            'title' => 'Test Company',
            'email' => 'test@example.com',
            'timezone' => 'Europe/Prague',
            'currency' => 'CZK',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Company::query()->where('title', 'Test Company')->exists())->toBeTrue();
});

it('can update a company', function () {
    $company = Company::factory()->create();

    Livewire::test(EditCompany::class, ['record' => $company->getRouteKey()])
        ->fillForm([
            'title' => 'Updated Company',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($company->fresh()->title)->toBe('Updated Company');
});

<?php

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the list page', function () {
    Livewire::test(ListCustomers::class)->assertSuccessful();
});

it('can render the create page', function () {
    Livewire::test(CreateCustomer::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $customer = Customer::factory()->create();

    Livewire::test(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->assertSuccessful();
});

it('can render the view page', function () {
    $customer = Customer::factory()->create();

    Livewire::test(ViewCustomer::class, ['record' => $customer->getRouteKey()])
        ->assertSuccessful();
});

it('can list customers', function () {
    $customers = Customer::factory()->count(3)->create();

    Livewire::test(ListCustomers::class)
        ->assertCanSeeTableRecords($customers);
});

it('can create a customer', function () {
    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'first_name' => 'Jan',
            'last_name' => 'Tester',
            'email' => 'jan.tester@example.com',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Customer::query()->where('email', 'jan.tester@example.com')->exists())->toBeTrue();
});

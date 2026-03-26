<?php

use App\Filament\Pages\ManageCalendarSettings;
use App\Models\User;
use App\Settings\CalendarSettings;
use Livewire\Livewire;

it('persists step_width in calendar settings', function (): void {
    $settings = app(CalendarSettings::class);
    $settings->step_width = 45;
    $settings->save();

    app()->forgetInstance(CalendarSettings::class);

    expect(app(CalendarSettings::class)->step_width)->toBe(45);
});

it('renders the manage calendar settings page', function (): void {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManageCalendarSettings::class)->assertSuccessful();
});

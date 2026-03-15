<?php

use App\Filament\Pages\AvatarCalendar;
use App\Filament\Widgets\AvatarCalendarWidget;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Staff;
use App\Models\User;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->user = User::factory()->create();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->user);

    $this->company = Company::factory()->create(['title' => 'Test Company']);
    $this->places = collect([
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room A', 'short_title' => 'RA']),
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room B', 'short_title' => 'RB']),
    ]);
    $this->staff = collect([
        Staff::factory()->create([
            'company_id' => $this->company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'avatar_url' => 'https://i.pravatar.cc/150?img=1',
            'color' => '#3b82f6',
            'is_active' => true,
        ]),
        Staff::factory()->create([
            'company_id' => $this->company->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'avatar_url' => null,
            'color' => '#ef4444',
            'is_active' => true,
        ]),
    ]);
});

test('avatar calendar page can be rendered by authenticated user', function () {
    $this->get(AvatarCalendar::getUrl())
        ->assertSuccessful();
});

test('guests cannot access the avatar calendar page', function () {
    auth()->logout();

    $this->get(AvatarCalendar::getUrl())
        ->assertRedirect();
});

test('avatar calendar widget has showStaffAvatarsInEvents enabled', function () {
    $widget = new AvatarCalendarWidget;

    expect($widget->showStaffAvatarsInEvents)->toBeTrue();
});

test('avatar calendar widget uses place_id as resource attribute', function () {
    $widget = new AvatarCalendarWidget;
    $reflection = new ReflectionMethod($widget, 'getResourceAttribute');

    expect($reflection->invoke($widget))->toBe('place_id');
});

test('avatar calendar widget can resolve full avatar URLs', function () {
    $widget = new AvatarCalendarWidget;

    $url = $widget->resolveStaffAvatarUrl('https://i.pravatar.cc/150?img=1');

    expect($url)->toBe('https://i.pravatar.cc/150?img=1');
});

test('avatar calendar widget returns null for null avatar URL', function () {
    $widget = new AvatarCalendarWidget;

    $url = $widget->resolveStaffAvatarUrl(null);

    expect($url)->toBeNull();
});

test('reservation with staff avatar can be created', function () {
    $staffWithAvatar = $this->staff->first();
    $place = $this->places->first();

    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'staff_id' => $staffWithAvatar->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $reservation->load('staff');

    expect($reservation->staff->avatar_url)->toBe('https://i.pravatar.cc/150?img=1');
    expect($reservation->staff->color)->toBe('#3b82f6');
});

test('reservation with staff without avatar falls back to initials', function () {
    $staffNoAvatar = $this->staff->last();
    $place = $this->places->first();

    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $place->id,
        'staff_id' => $staffNoAvatar->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $reservation->load('staff');

    expect($reservation->staff->avatar_url)->toBeNull();
    expect($reservation->staff->first_name)->toBe('Jane');
    expect($reservation->staff->last_name)->toBe('Smith');

    $initials = mb_strtoupper(mb_substr($reservation->staff->first_name, 0, 1).mb_substr($reservation->staff->last_name, 0, 1));
    expect($initials)->toBe('JS');
});

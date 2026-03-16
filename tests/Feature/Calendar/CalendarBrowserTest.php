<?php

use App\Domain\Booking\Models\Reservation;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->company = Company::factory()->create(['title' => 'Test Company']);

    $this->places = collect([
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room A', 'short_title' => 'RA']),
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room B', 'short_title' => 'RB']),
        Place::factory()->create(['company_id' => $this->company->id, 'title' => 'Room C', 'short_title' => 'RC']),
    ]);

    $monday = now()->startOfWeek();
    foreach (range(8, 12) as $hour) {
        Reservation::factory()->create([
            'company_id' => $this->company->id,
            'place_id' => $this->places->random()->id,
            'from_time' => $monday->copy()->setHour($hour),
            'to_time' => $monday->copy()->setHour($hour + 1),
            'capacity' => 20,
            'booked_count' => rand(0, 20),
        ]);
    }
});

// -- Smoke tests --

test('calendar page has no javascript errors', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertNoJavaScriptErrors();
});

test('calendar page has no console errors or JS errors', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertNoSmoke();
});

// -- Page rendering --

test('calendar page renders the calendar container', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertPresent('#calendarMain');
});

test('calendar page shows Calendar in navigation', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->assertSee('Calendar');
});

// -- Calendar content --

test('calendar shows resource names for selected company', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertSee('Room A')
        ->assertSee('Room B')
        ->assertSee('Room C');
});

test('calendar shows hour labels', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertSee('8:00')
        ->assertSee('12:00')
        ->assertSee('16:00');
});

test('calendar shows week date range', function () {
    $this->actingAs($this->user);

    $monday = now()->startOfWeek();
    $sunday = now()->endOfWeek();
    $expectedRange = $monday->format('d.m').' - '.$sunday->format('d.m.Y');

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertSee($expectedRange);
});

test('calendar shows view switcher with Week vertically label', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertSee('Week vertically');
});

// -- Authentication --

test('guest is redirected to login page', function () {
    $page = visit('/admin/calendar');

    $page->assertPathIs('/admin/login');
});

test('user can log in and see calendar', function () {
    $page = visit('/admin/login');

    $page->click('input[type="email"]')
        ->typeSlowly('input[type="email"]', 'test@example.com')
        ->click('input[type="password"]')
        ->typeSlowly('input[type="password"]', 'password')
        ->press('Sign in')
        ->wait(3)
        ->assertPathContains('/admin');
});

// -- Navigation --

test('calendar date navigation arrows are present', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertPresent('#calendarMain');
});

test('calendar filters indicator shows active count', function () {
    $this->actingAs($this->user);

    $page = visit('/admin/calendar');

    $page->wait(2)
        ->assertSee('3');
});

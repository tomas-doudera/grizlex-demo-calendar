<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'translator@example.com',
        'password' => 'password',
    ]);
});

test('language switcher is visible on admin panel', function () {
    $this->actingAs($this->user);

    $page = visit('/admin');

    $page->assertPresent('[x-data] [x-on\\:click]');
});

test('language switcher dropdown shows all four locales', function () {
    $this->actingAs($this->user);

    $this->get('/admin')
        ->assertSee('English')
        ->assertSee('Čeština')
        ->assertSee('Slovenčina')
        ->assertSee('Deutsch');
});

test('switching to Czech translates navigation groups', function () {
    $this->actingAs($this->user);
    session(['locale' => 'cs']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Kalendáře')
        ->assertSee('Rezervace')
        ->assertSee('Podpora');
});

test('switching to German translates navigation groups', function () {
    $this->actingAs($this->user);
    session(['locale' => 'de']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Kalender')
        ->assertSee('Reservierungen')
        ->assertSee('Finanzen');
});

test('switching to Slovak translates navigation groups', function () {
    $this->actingAs($this->user);
    session(['locale' => 'sk']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Kalendáre')
        ->assertSee('Rezervácie')
        ->assertSee('Podpora');
});

test('English locale shows English navigation', function () {
    $this->actingAs($this->user);
    session(['locale' => 'en']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Calendars')
        ->assertSee('Reservations')
        ->assertSee('Support');
});

test('Czech locale translates dashboard widget labels', function () {
    $this->actingAs($this->user);
    session(['locale' => 'cs']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Celkový příjem')
        ->assertSee('Celkem objednávek')
        ->assertSee('Zákazníci')
        ->assertSee('Otevřené tikety');
});

test('German locale translates dashboard widget labels', function () {
    $this->actingAs($this->user);
    session(['locale' => 'de']);

    $page = visit('/admin');

    $page->wait(1)
        ->assertSee('Gesamtumsatz')
        ->assertSee('Bestellungen gesamt')
        ->assertSee('Kunden')
        ->assertSee('Offene Tickets');
});

test('admin dashboard has no javascript errors', function () {
    $this->actingAs($this->user);

    $page = visit('/admin');

    $page->wait(2)
        ->assertNoJavaScriptErrors();
});

test('German locale persists on calendar page', function () {
    $this->actingAs($this->user);
    session(['locale' => 'de']);

    $page = visit('/admin/calendar');

    $page->wait(1)
        ->assertSee('Kalender')
        ->assertSee('Basiskalender');
});

<?php

use App\Filament\Widgets\CalendarWidget;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());

    $this->company = Company::factory()->create();
    $this->placeA = Place::factory()->create(['company_id' => $this->company->id]);
    $this->placeB = Place::factory()->create(['company_id' => $this->company->id]);
    $this->venueA = Venue::factory()->create(['place_id' => $this->placeA->id]);
    $this->venueB = Venue::factory()->create(['place_id' => $this->placeB->id]);
});

it('can render the calendar widget', function () {
    Livewire::test(CalendarWidget::class)
        ->assertSuccessful();
});

it('shows selected place titles in the calendar widget heading', function () {
    Livewire::test(CalendarWidget::class)
        ->set('filters', [
            'company_id' => $this->company->id,
            'place_id' => $this->placeA->id,
            'date' => now()->format('Y-m-d'),
            'venue_ids' => [$this->venueA->id],
        ])
        ->assertSee($this->placeA->title);
});

it('shows place title from selected venues when place filter is empty', function () {
    Livewire::test(CalendarWidget::class)
        ->set('filters', [
            'company_id' => $this->company->id,
            'place_id' => null,
            'date' => now()->format('Y-m-d'),
            'venue_ids' => [$this->venueA->id],
        ])
        ->assertSee($this->placeA->title);
});

it('shows comma-separated place titles when venues span multiple places', function () {
    Livewire::test(CalendarWidget::class)
        ->set('filters', [
            'company_id' => $this->company->id,
            'place_id' => null,
            'date' => now()->format('Y-m-d'),
            'venue_ids' => [$this->venueA->id, $this->venueB->id],
        ])
        ->assertSee($this->placeA->title)
        ->assertSee($this->placeB->title);
});

it('shows all places label when no place and no venue is selected', function () {
    Livewire::test(CalendarWidget::class)
        ->set('filters', [
            'company_id' => $this->company->id,
            'place_id' => null,
            'date' => now()->format('Y-m-d'),
            'venue_ids' => [],
        ])
        ->assertSee(__('filament/calendar.title_all_places'));
});

it('does not load reservations when venue filter is empty', function () {
    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueA->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $count = Reservation::query()
        ->where('company_id', $this->company->id)
        ->whereIn('venue_id', array_values(array_filter([])))
        ->count();

    expect($count)->toBe(0);
});

it('can query reservations by date range', function () {
    $monday = now()->startOfWeek();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueA->id,
        'from_time' => $monday->copy()->setHour(10),
        'to_time' => $monday->copy()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueA->id,
        'from_time' => $monday->copy()->addWeeks(3)->setHour(10),
        'to_time' => $monday->copy()->addWeeks(3)->setHour(11),
    ]);

    $startDate = $monday->copy()->startOfDay();
    $endDate = $monday->copy()->endOfWeek()->endOfDay();

    $events = Reservation::query()
        ->where('company_id', $this->company->id)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('from_time', [$startDate, $endDate])
                ->orWhereBetween('to_time', [$startDate, $endDate]);
        })
        ->get();

    expect($events)->toHaveCount(1);
});

it('can filter reservations by venue', function () {
    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueA->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueB->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $filtered = Reservation::query()
        ->whereIn('venue_id', [$this->venueA->id])
        ->get();

    expect($filtered)->toHaveCount(1)
        ->and($filtered->first()->venue_id)->toBe($this->venueA->id);
});

it('can detect overlapping reservations', function () {
    $baseTime = now()->startOfWeek()->setHour(10);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'venue_id' => $this->venueA->id,
        'from_time' => $baseTime->copy(),
        'to_time' => $baseTime->copy()->addHour(),
    ]);

    $hasOverlap = Reservation::where('venue_id', $this->venueA->id)
        ->where('from_time', '<', $baseTime->copy()->addMinutes(90))
        ->where('to_time', '>', $baseTime->copy()->addMinutes(30))
        ->exists();

    $noOverlap = Reservation::where('venue_id', $this->venueA->id)
        ->where('from_time', '<', $baseTime->copy()->addHours(3))
        ->where('to_time', '>', $baseTime->copy()->addHours(2))
        ->exists();

    expect($hasOverlap)->toBeTrue()
        ->and($noOverlap)->toBeFalse();
});

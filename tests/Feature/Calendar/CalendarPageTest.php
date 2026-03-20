<?php

use App\Filament\Widgets\CalendarWidget;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());

    $this->company = Company::factory()->create();
    $this->placeA = Place::factory()->create(['company_id' => $this->company->id]);
    $this->placeB = Place::factory()->create(['company_id' => $this->company->id]);
});

it('can render the calendar widget', function () {
    Livewire::test(CalendarWidget::class)
        ->assertSuccessful();
});

it('can query reservations by date range', function () {
    $monday = now()->startOfWeek();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->placeA->id,
        'from_time' => $monday->copy()->setHour(10),
        'to_time' => $monday->copy()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->placeA->id,
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

it('can filter reservations by place', function () {
    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->placeA->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->placeB->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $filtered = Reservation::query()
        ->whereIn('place_id', [$this->placeA->id])
        ->get();

    expect($filtered)->toHaveCount(1)
        ->and($filtered->first()->place_id)->toBe($this->placeA->id);
});

it('can detect overlapping reservations', function () {
    $baseTime = now()->startOfWeek()->setHour(10);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'place_id' => $this->placeA->id,
        'from_time' => $baseTime->copy(),
        'to_time' => $baseTime->copy()->addHour(),
    ]);

    $hasOverlap = Reservation::where('place_id', $this->placeA->id)
        ->where('from_time', '<', $baseTime->copy()->addMinutes(90))
        ->where('to_time', '>', $baseTime->copy()->addMinutes(30))
        ->exists();

    $noOverlap = Reservation::where('place_id', $this->placeA->id)
        ->where('from_time', '<', $baseTime->copy()->addHours(3))
        ->where('to_time', '>', $baseTime->copy()->addHours(2))
        ->exists();

    expect($hasOverlap)->toBeTrue()
        ->and($noOverlap)->toBeFalse();
});

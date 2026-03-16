<?php

use App\Domain\Booking\Filament\Pages\StaffCalendar;
use App\Domain\Booking\Models\Reservation;
use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\User;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->user = User::factory()->create();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->user);

    $this->company = Company::factory()->create(['title' => 'Test Company']);
    $this->staff = collect([
        Staff::factory()->create([
            'company_id' => $this->company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'avatar_url' => 'https://example.com/avatar-john.jpg',
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

test('staff calendar page can be rendered by authenticated user', function () {
    $this->get(StaffCalendar::getUrl())
        ->assertSuccessful();
});

test('guests cannot access the staff calendar page', function () {
    auth()->logout();

    $this->get(StaffCalendar::getUrl())
        ->assertRedirect();
});

test('staff member with avatar has avatar_url accessible', function () {
    $staffWithAvatar = $this->staff->first();

    expect($staffWithAvatar->avatar_url)->toBe('https://example.com/avatar-john.jpg');
    expect($staffWithAvatar->full_name)->toBe('John Doe');
});

test('staff member without avatar has null avatar_url', function () {
    $staffWithoutAvatar = $this->staff->last();

    expect($staffWithoutAvatar->avatar_url)->toBeNull();
    expect($staffWithoutAvatar->full_name)->toBe('Jane Smith');
});

test('reservation belongs to a staff member', function () {
    $staffMember = $this->staff->first();
    $reservation = Reservation::factory()->create([
        'company_id' => $this->company->id,
        'staff_id' => $staffMember->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($reservation->staff)->toBeInstanceOf(Staff::class);
    expect($reservation->staff->id)->toBe($staffMember->id);
});

test('staff member has many reservations', function () {
    $staffMember = $this->staff->first();

    Reservation::factory()->count(3)->create([
        'company_id' => $this->company->id,
        'staff_id' => $staffMember->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    expect($staffMember->reservations)->toHaveCount(3);
});

test('reservations can be filtered by staff ids', function () {
    $staffA = $this->staff->first();
    $staffB = $this->staff->last();

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'staff_id' => $staffA->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'staff_id' => $staffB->id,
        'from_time' => now()->setHour(10),
        'to_time' => now()->setHour(11),
    ]);

    $filtered = Reservation::query()
        ->whereIn('staff_id', [$staffA->id])
        ->get();

    expect($filtered)->toHaveCount(1);
    expect($filtered->first()->staff_id)->toBe($staffA->id);
});

test('overlapping reservations for the same staff can be detected', function () {
    $staffMember = $this->staff->first();
    $baseTime = now()->startOfWeek()->setHour(10);

    Reservation::factory()->create([
        'company_id' => $this->company->id,
        'staff_id' => $staffMember->id,
        'from_time' => $baseTime->copy(),
        'to_time' => $baseTime->copy()->addHour(),
    ]);

    $newFrom = $baseTime->copy()->addMinutes(30);
    $newTo = $baseTime->copy()->addMinutes(90);

    $hasOverlap = Reservation::where('staff_id', $staffMember->id)
        ->where('from_time', '<', $newTo)
        ->where('to_time', '>', $newFrom)
        ->exists();

    expect($hasOverlap)->toBeTrue();

    $noOverlap = Reservation::where('staff_id', $staffMember->id)
        ->where('from_time', '<', $baseTime->copy()->addHours(3))
        ->where('to_time', '>', $baseTime->copy()->addHours(2))
        ->exists();

    expect($noOverlap)->toBeFalse();
});

test('inactive staff are excluded from the calendar resources', function () {
    Staff::factory()->create([
        'company_id' => $this->company->id,
        'first_name' => 'Inactive',
        'last_name' => 'Worker',
        'is_active' => false,
    ]);

    $activeStaff = Staff::query()
        ->where('company_id', $this->company->id)
        ->where('is_active', true)
        ->get();

    expect($activeStaff)->toHaveCount(2);
    expect($activeStaff->pluck('first_name')->toArray())->not->toContain('Inactive');
});

test('staff full name accessor returns correct value', function () {
    $staff = $this->staff->first();

    expect($staff->full_name)->toBe('John Doe');
});

test('staff color is accessible', function () {
    $staff = $this->staff->first();

    expect($staff->color)->toBe('#3b82f6');
});

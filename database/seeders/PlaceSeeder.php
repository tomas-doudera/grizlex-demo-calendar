<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Place;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->first();

        if (! $company) {
            return;
        }

        $openingHoursMain = [
            'monday' => [8, 20],
            'tuesday' => [8, 20],
            'wednesday' => [8, 20],
            'thursday' => [8, 20],
            'friday' => [8, 20],
            'saturday' => [8, 20],
            'sunday' => [8, 20],
        ];

        $place = Place::query()->create([
            'company_id' => $company->id,
            'title' => 'Praha Centrum',
            'description' => 'Hlavní pobočka',
            'email' => 'praha@example.com',
            'phone' => '+420 123 456 789',
            'address' => 'Václavské náměstí 1',
            'city' => 'Praha',
            'postal_code' => '11000',
            'country' => 'CZ',
            'is_active' => true,
            'sort_order' => 0,
            'opening_hours' => $openingHoursMain,
        ]);

        $venues = [
            [
                'title' => 'Cardio Zone',
                'short_title' => 'CZ',
                'type' => 'zone',
                'capacity' => 20,
                'color' => '#ef4444',
            ],
            [
                'title' => 'Strength Zone',
                'short_title' => 'SZ',
                'type' => 'zone',
                'capacity' => 15,
                'color' => '#3b82f6',
            ],
            [
                'title' => 'Yoga Studio',
                'short_title' => 'YS',
                'type' => 'studio',
                'capacity' => 12,
                'color' => '#8b5cf6',
            ],
            [
                'title' => 'Spinning Room',
                'short_title' => 'SP',
                'type' => 'room',
                'capacity' => 25,
                'color' => '#f59e0b',
            ],
            [
                'title' => 'Squash Court 1',
                'short_title' => 'S1',
                'type' => 'court',
                'capacity' => 4,
                'color' => '#10b981',
            ],
        ];

        foreach ($venues as $venue) {
            Venue::query()->create([
                'place_id' => $place->id,
                'is_active' => true,
                'sort_order' => 0,
                ...$venue,
            ]);
        }
    }
}

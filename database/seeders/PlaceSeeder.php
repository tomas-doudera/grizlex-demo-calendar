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
            'monday' => '06:00-22:00',
            'tuesday' => '06:00-22:00',
            'wednesday' => '06:00-22:00',
            'thursday' => '06:00-22:00',
            'friday' => '06:00-22:00',
            'saturday' => '08:00-20:00',
            'sunday' => '08:00-18:00',
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

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
                'type' => 'zone',
                'capacity' => 20,
            ],
            [
                'title' => 'Strength Zone',
                'type' => 'zone',
                'capacity' => 15,
            ],
            [
                'title' => 'Yoga Studio',
                'type' => 'studio',
                'capacity' => 12,
            ],
            [
                'title' => 'Spinning Room',
                'type' => 'room',
                'capacity' => 25,
            ],
            [
                'title' => 'Squash Court 1',
                'type' => 'court',
                'capacity' => 4,
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

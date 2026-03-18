<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->first();

        if (! $company) {
            return;
        }

        $places = [
            ['title' => 'Cardio Zone', 'short_title' => 'CZ', 'type' => 'zone', 'capacity' => 20, 'color' => '#ef4444'],
            ['title' => 'Strength Zone', 'short_title' => 'SZ', 'type' => 'zone', 'capacity' => 15, 'color' => '#3b82f6'],
            ['title' => 'Yoga Studio', 'short_title' => 'YS', 'type' => 'studio', 'capacity' => 12, 'color' => '#8b5cf6'],
            ['title' => 'Spinning Room', 'short_title' => 'SP', 'type' => 'room', 'capacity' => 25, 'color' => '#f59e0b'],
            ['title' => 'Squash Court 1', 'short_title' => 'S1', 'type' => 'court', 'capacity' => 4, 'color' => '#10b981'],
        ];

        foreach ($places as $place) {
            Place::factory()->create([
                'company_id' => $company->id,
                ...$place,
            ]);
        }
    }
}

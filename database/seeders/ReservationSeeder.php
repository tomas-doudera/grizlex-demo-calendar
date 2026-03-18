<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $places = Place::with('company')->get();

        foreach ($places as $place) {
            Reservation::factory()
                ->count(3)
                ->create([
                    'company_id' => $place->company_id,
                    'place_id' => $place->id,
                ]);
        }
    }
}

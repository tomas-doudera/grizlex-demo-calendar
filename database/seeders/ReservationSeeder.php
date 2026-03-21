<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $places = Place::with('company')->get();

        foreach ($places as $place) {
            $staffIds = Staff::where('company_id', $place->company_id)->pluck('id');

            Reservation::factory()
                ->count(3)
                ->create([
                    'company_id' => $place->company_id,
                    'place_id' => $place->id,
                    'staff_id' => $staffIds->random(),
                    'service_id' => Service::where('company_id', $place->company_id)->pluck('id')->random(),
                ]);
        }
    }
}

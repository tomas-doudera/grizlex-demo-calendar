<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $venues = Venue::with('place')->get();

        foreach ($venues as $venue) {
            $companyId = $venue->place->company_id;
            $staffIds = Staff::where('company_id', $companyId)->pluck('id');

            $reservations = Reservation::factory()
                ->count(3)
                ->create([
                    'company_id' => $companyId,
                    'venue_id' => $venue->id,
                    'staff_id' => $staffIds->random(),
                    'service_id' => Service::where('company_id', $companyId)->pluck('id')->random(),
                ]);

            foreach ($reservations as $reservation) {
                $customerIds = Customer::query()
                    ->inRandomOrder()
                    ->limit(fake()->numberBetween(1, 2))
                    ->pluck('id');

                $reservation->customers()->attach($customerIds);
            }
        }
    }
}

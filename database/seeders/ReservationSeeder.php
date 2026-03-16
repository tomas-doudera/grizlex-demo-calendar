<?php

namespace Database\Seeders;

use App\Domain\Booking\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 3) as $company) {
            foreach (range(0, 14) as $day) {
                foreach (range(8, 15) as $hour) {
                    foreach (range(...match ($company) {
                        1 => [1, 3],
                        2 => [4, 6],
                        3 => [7, 9],
                    }) as $place) {
                        $capacity = random_int(1, 30);
                        if (rand(0, 1)) {
                            Reservation::factory()->create([
                                'place_id' => $place,
                                'company_id' => $company,
                                'from_time' => now()->startOfWeek()->addDays($day)->setTime($hour, 0),
                                'to_time' => now()->startOfWeek()->addDays($day)->setTime($hour + 1, 0),
                                'capacity' => $capacity,
                                'booked_count' => random_int(0, $capacity),
                            ]);
                        }
                    }
                }
            }
        }
    }
}

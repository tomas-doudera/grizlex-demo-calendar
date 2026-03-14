<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 week');
        $endTime = (clone $startTime)->modify('+2 hours');

        return [
            'company_id' => Company::factory(),
            'place_id' => Place::factory(),
            'from_time' => $startTime,
            'to_time' => $endTime,
        ];
    }
}

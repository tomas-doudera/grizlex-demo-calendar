<?php

namespace App\Domain\Booking\Database\Factories;

use App\Domain\Shared\Models\Company;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Booking\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

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

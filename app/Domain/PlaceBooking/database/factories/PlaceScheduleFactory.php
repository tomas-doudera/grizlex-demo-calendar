<?php

namespace App\Domain\PlaceBooking\Database\Factories;

use App\Domain\PlaceBooking\Models\Place;
use App\Domain\PlaceBooking\Models\PlaceSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlaceSchedule>
 */
class PlaceScheduleFactory extends Factory
{
    protected $model = PlaceSchedule::class;

    public function definition(): array
    {
        $startHour = fake()->numberBetween(6, 10);
        $endHour = fake()->numberBetween(18, 22);

        return [
            'place_id' => Place::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', $endHour),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}

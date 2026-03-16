<?php

namespace App\Domain\IndividualBooking\Database\Factories;

use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\IndividualBooking\Models\StaffSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffSchedule>
 */
class StaffScheduleFactory extends Factory
{
    protected $model = StaffSchedule::class;

    public function definition(): array
    {
        $startHour = fake()->numberBetween(6, 10);
        $endHour = fake()->numberBetween(16, 20);

        return [
            'staff_id' => Staff::factory(),
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

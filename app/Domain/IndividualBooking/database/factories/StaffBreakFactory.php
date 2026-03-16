<?php

namespace App\Domain\IndividualBooking\Database\Factories;

use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\IndividualBooking\Models\StaffBreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffBreak>
 */
class StaffBreakFactory extends Factory
{
    protected $model = StaffBreak::class;

    public function definition(): array
    {
        return [
            'staff_id' => Staff::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'date' => null,
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'reason' => fake()->optional(0.5)->randomElement(['Lunch', 'Personal', 'Meeting']),
        ];
    }

    public function oneOff(): static
    {
        return $this->state([
            'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'day_of_week' => null,
        ]);
    }
}

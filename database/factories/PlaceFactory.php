<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Place>
 */
class PlaceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->words(2, true),
            'short_title' => fake()->lexify('??'),
            'description' => fake()->optional()->sentence(),
            'type' => fake()->randomElement(['room', 'court', 'zone', 'studio']),
            'capacity' => fake()->numberBetween(1, 30),
            'color' => fake()->hexColor(),
            'is_active' => true,
            'sort_order' => 0,
            'min_booking_minutes' => 30,
            'max_booking_minutes' => 120,
            'booking_interval_minutes' => 15,
            'advance_booking_days' => 30,
            'cancellation_hours' => 24,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}

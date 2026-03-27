<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'place_id' => Place::factory(),
            'title' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'type' => fake()->randomElement(['room', 'court', 'zone', 'studio']),
            'capacity' => fake()->numberBetween(1, 30),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}

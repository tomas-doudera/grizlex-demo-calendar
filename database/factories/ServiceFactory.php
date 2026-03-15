<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->randomElement([
                'Yoga Class', 'Personal Training', 'Group Fitness', 'Pilates',
                'Swimming Lesson', 'Tennis Court', 'Spa Treatment', 'Massage',
                'Sauna Session', 'Boxing Class', 'CrossFit', 'Meditation',
                'Dance Class', 'Cycling Class', 'Rock Climbing',
            ]),
            'description' => fake()->sentence(),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'price' => fake()->randomFloat(2, 10, 150),
            'color' => fake()->randomElement(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899']),
            'is_active' => fake()->boolean(90),
            'max_participants' => fake()->randomElement([1, 5, 10, 15, 20, 30]),
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'duration_minutes' => fake()->randomNumber(),
            'price' => fake()->randomFloat(2, 0, 999999.99),
            'color' => fake()->word(),
            'is_active' => fake()->boolean(),
            'sort_order' => fake()->randomNumber(),
        ];
    }
}

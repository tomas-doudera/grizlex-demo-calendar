<?php

namespace Database\Factories;

use App\Enums\StaffRole;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'role' => fake()->randomElement(StaffRole::cases()),
            'specialization' => fake()->optional()->randomElement([
                'Yoga', 'Pilates', 'Swimming', 'Boxing', 'CrossFit', 'Tennis',
                'Personal Training', 'Massage Therapy', 'Nutrition',
            ]),
            'bio' => fake()->optional(0.6)->paragraph(),
            'is_active' => fake()->boolean(90),
            'color' => fake()->randomElement([
                '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6',
                '#3b82f6', '#6366f1', '#a855f7', '#ec4899', '#f43f5e',
            ]),
        ];
    }
}

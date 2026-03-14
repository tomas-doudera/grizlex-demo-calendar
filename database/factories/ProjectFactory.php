<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $budget = fake()->randomFloat(2, 5000, 200000);
        $status = fake()->randomElement(ProjectStatus::cases());
        $progress = match ($status) {
            ProjectStatus::Planning => fake()->numberBetween(0, 10),
            ProjectStatus::Active => fake()->numberBetween(10, 90),
            ProjectStatus::OnHold => fake()->numberBetween(20, 70),
            ProjectStatus::Completed => 100,
            ProjectStatus::Cancelled => fake()->numberBetween(5, 50),
        };

        return [
            'customer_id' => Customer::factory(),
            'name' => fake()->bs(),
            'description' => fake()->paragraph(),
            'status' => $status,
            'progress' => $progress,
            'budget' => $budget,
            'spent' => round($budget * ($progress / 100) * fake()->randomFloat(2, 0.8, 1.2), 2),
            'color' => fake()->randomElement(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899']),
            'start_date' => fake()->dateTimeBetween('-1 year', '-1 month'),
            'due_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
            'completed_at' => $status === ProjectStatus::Completed ? fake()->dateTimeBetween('-3 months') : null,
            'is_pinned' => fake()->boolean(20),
        ];
    }
}

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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = fake()->unique()->email();

        return [
            'company_id' => Company::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => $email,
            'phone' => fake()->phoneNumber(),
            'role' => fake()->randomElement(StaffRole::class),
            'avatar_url' => 'https://i.pravatar.cc/150?u='.$email,
            'color' => fake()->hexColor(),
            'bio' => fake()->paragraph(),
            'is_active' => fake()->boolean(100),
        ];
    }
}

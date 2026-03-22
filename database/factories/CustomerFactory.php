<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'company_name' => fake()->optional(0.6)->company(),
            'address' => fake()->optional()->streetAddress(),
            'city' => fake()->optional()->city(),
            'state' => fake()->optional()->state(),
            'postal_code' => fake()->optional()->postcode(),
            'country' => fake()->randomElement(['US', 'UK', 'DE', 'FR', 'CZ', 'CA', 'AU']),
            'date_of_birth' => fake()->optional(0.4)->dateTimeBetween('-70 years', '-18 years'),
            'notes' => fake()->optional(0.3)->sentence(),
            'is_active' => fake()->boolean(90),
            'is_vip' => false,
            'lifetime_value' => fake()->randomFloat(2, 0, 50000),
            'avatar_url' => null,
        ];
    }
}

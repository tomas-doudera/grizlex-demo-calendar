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
            'description' => fake()->optional()->sentence(),
            'email' => fake()->optional()->companyEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'address' => fake()->optional()->streetAddress(),
            'city' => fake()->optional()->city(),
            'postal_code' => fake()->optional()->postcode(),
            'country' => fake()->optional()->countryCode(),
            'is_active' => true,
            'sort_order' => 0,
            'opening_hours' => [
                'monday' => '09:00-18:00',
                'tuesday' => '09:00-18:00',
                'wednesday' => '09:00-18:00',
                'thursday' => '09:00-18:00',
                'friday' => '09:00-18:00',
                'saturday' => '10:00-14:00',
                'sunday' => '',
            ],
            'deleted_at' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}

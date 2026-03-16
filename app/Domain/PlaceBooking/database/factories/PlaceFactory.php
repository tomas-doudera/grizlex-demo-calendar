<?php

namespace App\Domain\PlaceBooking\Database\Factories;

use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Place>
 */
class PlaceFactory extends Factory
{
    protected $model = Place::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->city(),
            'short_title' => $this->faker->citySuffix(),
        ];
    }
}

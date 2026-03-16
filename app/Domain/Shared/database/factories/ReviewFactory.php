<?php

namespace App\Domain\Shared\Database\Factories;

use App\Domain\Shared\Models\Customer;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Shared\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'customer_id' => Customer::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional(0.7)->paragraph(),
            'is_published' => fake()->boolean(85),
        ];
    }
}

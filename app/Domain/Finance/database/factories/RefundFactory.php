<?php

namespace App\Domain\Finance\Database\Factories;

use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Models\Refund;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Refund>
 */
class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'amount' => fake()->randomFloat(2, 10, 200),
            'reason' => fake()->optional(0.7)->sentence(),
            'refund_number' => 'REF-'.fake()->unique()->numerify('######'),
            'refunded_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(PaymentStatus::cases());

        return [
            'reservation_id' => Reservation::factory(),
            'customer_id' => Customer::factory(),
            'payment_number' => 'PAY-'.fake()->unique()->numerify('######'),
            'amount' => fake()->randomFloat(2, 10, 500),
            'status' => $status,
            'method' => fake()->randomElement(PaymentMethod::cases()),
            'transaction_id' => $status === PaymentStatus::Paid ? fake()->uuid() : null,
            'notes' => fake()->optional(0.2)->sentence(),
            'paid_at' => $status === PaymentStatus::Paid ? fake()->dateTimeBetween('-3 months') : null,
        ];
    }
}

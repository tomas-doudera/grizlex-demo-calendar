<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 2000);
        $tax = round($subtotal * 0.21, 2);
        $shipping = fake()->randomElement([0, 5.99, 9.99, 14.99]);
        $discount = fake()->boolean(30) ? round($subtotal * fake()->randomFloat(2, 0.05, 0.25), 2) : 0;
        $total = round($subtotal + $tax + $shipping - $discount, 2);
        $status = fake()->randomElement(OrderStatus::cases());

        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('######'),
            'status' => $status,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'currency' => 'USD',
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'bank_transfer', 'stripe']),
            'shipping_address' => fake()->address(),
            'billing_address' => fake()->address(),
            'notes' => fake()->optional(0.2)->sentence(),
            'is_paid' => in_array($status, [OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Delivered]),
            'paid_at' => in_array($status, [OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Delivered]) ? fake()->dateTimeBetween('-6 months') : null,
            'shipped_at' => in_array($status, [OrderStatus::Shipped, OrderStatus::Delivered]) ? fake()->dateTimeBetween('-3 months') : null,
            'delivered_at' => $status === OrderStatus::Delivered ? fake()->dateTimeBetween('-1 month') : null,
        ];
    }
}

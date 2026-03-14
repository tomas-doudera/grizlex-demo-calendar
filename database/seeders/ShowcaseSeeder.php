<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::factory(8)->create();

        $products = Product::factory(40)
            ->recycle($categories)
            ->create();

        $customers = Customer::factory(25)->create();

        $vipCustomers = Customer::factory(5)->vip()->create();
        $allCustomers = $customers->merge($vipCustomers);

        $users = User::all();

        $allCustomers->each(function (Customer $customer) use ($products) {
            $orderCount = fake()->numberBetween(0, 5);

            Order::factory($orderCount)
                ->for($customer)
                ->create()
                ->each(function (Order $order) use ($products) {
                    $itemCount = fake()->numberBetween(1, 4);

                    $selectedProducts = $products->random(min($itemCount, $products->count()));

                    $selectedProducts->each(function (Product $product) use ($order) {
                        $quantity = fake()->numberBetween(1, 3);

                        OrderItem::factory()->create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                            'total' => round($quantity * (float) $product->price, 2),
                        ]);
                    });

                    $itemsTotal = $order->items()->sum('total');
                    $order->update([
                        'subtotal' => $itemsTotal,
                        'total' => round((float) $itemsTotal + (float) $order->tax + (float) $order->shipping_cost - (float) $order->discount, 2),
                    ]);
                });
        });

        $projects = Project::factory(12)
            ->recycle($allCustomers)
            ->create();

        $tickets = Ticket::factory(35)
            ->recycle($allCustomers)
            ->recycle($projects)
            ->recycle($users)
            ->create();

        $tickets->each(function (Ticket $ticket) use ($users) {
            Comment::factory(fake()->numberBetween(0, 5))
                ->for($ticket)
                ->recycle($users)
                ->create();
        });

        $allCustomers->each(function (Customer $customer) {
            $customer->update([
                'lifetime_value' => $customer->orders()->where('is_paid', true)->sum('total'),
            ]);
        });
    }
}

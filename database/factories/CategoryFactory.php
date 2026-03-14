<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Electronics', 'Clothing', 'Home & Garden', 'Sports', 'Books',
            'Toys', 'Health & Beauty', 'Automotive', 'Food & Drink', 'Office Supplies',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
            'is_visible' => fake()->boolean(85),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}

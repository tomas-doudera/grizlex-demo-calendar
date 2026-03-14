<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(fake()->numberBetween(2, 4), true);
        $price = fake()->randomFloat(2, 5, 999);

        return [
            'category_id' => Category::factory(),
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper(fake()->unique()->bothify('???-####')),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => fake()->boolean(30) ? $price * 1.2 : null,
            'cost' => round($price * 0.6, 2),
            'stock_quantity' => fake()->numberBetween(0, 500),
            'status' => fake()->randomElement(ProductStatus::cases()),
            'is_featured' => fake()->boolean(20),
            'is_visible' => fake()->boolean(90),
            'requires_shipping' => fake()->boolean(85),
            'weight' => fake()->optional()->randomFloat(2, 0.1, 50),
            'barcode' => fake()->optional()->ean13(),
            'tags' => fake()->randomElements(['new', 'sale', 'bestseller', 'limited', 'eco-friendly', 'premium'], fake()->numberBetween(0, 3)),
            'metadata' => null,
            'color' => fake()->optional()->hexColor(),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-1 year'),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => ProductStatus::Active]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true, 'status' => ProductStatus::Active]);
    }
}

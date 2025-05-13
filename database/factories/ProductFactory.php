<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'sku' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(0, 1000, 90000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean,
            'category_id' => Category::query()->inRandomOrder()->first()->id,
            'unit_measure' => $this->faker->randomElement(['pcs', 'kg', 'lbs', 'g']),
            'stock_unit_measure' => $this->faker->randomElement(['pcs', 'kg', 'lbs', 'g']),
        ];
    }
}

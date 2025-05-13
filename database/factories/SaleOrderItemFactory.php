<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleOrderItem>
 */
class SaleOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::query()->inRandomOrder()->first();
        $order = SaleOrder::query()->inRandomOrder()->first();
        return [
            'sale_order_id'=> $order->id,
            'product_id'=> $product->id,
            'quantity'=>$this->faker->numberBetween(1,10),
            'price' => $product->price,
        ];
    }
}

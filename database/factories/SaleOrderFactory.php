<?php

namespace Database\Factories;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleOrder>
 */
class SaleOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::query()->inRandomOrder()->first()->id,
            'invoice_number' => $this->faker->unique()->numerify('INV-###'),
            'order_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'done_by' => \App\Models\User::query()->inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement([Status::ORDER, Status::DELIVERED, Status::PARTIALLY_DELIVERED, Status::CANCELLED]),
            'total_amount' => $this->faker->numberBetween(100, 1000),
        ];
    }
}

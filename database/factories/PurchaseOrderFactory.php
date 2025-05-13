<?php

namespace Database\Factories;

use App\Constants\Status;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::query()->inRandomOrder()->first()->id,
            'invoice_number' => $this->faker->unique()->numerify('VN-###'),
            'delivery_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'done_by' => User::query()->inRandomOrder()->first()->id,
        ];
    }
}

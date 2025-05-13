<?php

namespace Database\Factories;

use App\Constants\Status;
use App\Models\PaymentMethod;
use App\Models\SaleOrder;
use App\Models\SalePayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SalePaymentFactory extends Factory
{
    protected $model = SalePayment::class;

    public function definition(): array
    {
        $first = SaleOrder::query()->inRandomOrder()->first();
        $amount= $first->getTotalAttribute();
        return [
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'currency' => $this->faker->word(),
            'note' => $this->faker->word(),
            'status' => Status::PAID,
            'reference' => $this->faker->word(),
            'amount' => $amount,
            'sale_order_id' => $first->id,
            'payment_method_id' => PaymentMethod::query()->inRandomOrder()->first()->id,
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'customer_id'=> $first->customer_id,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\SaleOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Random\RandomException;

class SaleOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     * @throws \Throwable
     */
    public function run(): void
    {
        \DB::beginTransaction();
        for ($i = 0; $i < 240; $i++) {
            $random_int = random_int(0, 12);
            $arr = [\App\Constants\Status::ORDER, \App\Constants\Status::DELIVERED, \App\Constants\Status::PARTIALLY_DELIVERED, \App\Constants\Status::CANCELLED];
            $order = SaleOrder::query()
                ->create([
                    'customer_id' => \App\Models\Customer::query()->inRandomOrder()->first()->id,
                    'invoice_number' => 'INV-###',
                    'order_date' => now()->subMonths($random_int),
                    'done_by' => \App\Models\User::query()->inRandomOrder()->first()->id,
                    'status' => $arr[random_int(0, count($arr) - 1)],
                    'total_amount' => 0
                ]);

            $total = 0;

            for ($j = 0; $j < random_int(1, 10); $j++) {
                $qty = random_int(1, 50);
                $price = random_int(1000, 100000);
                $order->items()->create([
                    'product_id' => \App\Models\Product::query()->inRandomOrder()->first()->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);
                $total += $qty * $price;
            }
            $order->update(['total_amount' => $total]);
            $order->generateInvoiceNumber();
        }
        \DB::commit();
    }
}

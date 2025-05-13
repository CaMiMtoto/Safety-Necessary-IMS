<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use Database\Factories\PurchaseOrderItemFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Random\RandomException;

class PurchaserItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $orders = PurchaseOrder::query()->get();
        foreach ($orders as $order) {
            $random_int = random_int(3, 10);
            for ($i = 0; $i < $random_int; $i++) {
                $order->items()->create([
                    'product_id' => \App\Models\Product::query()->inRandomOrder()->first()->id,
                    'quantity' => random_int(1, 50),
                    'price' => random_int(1000, 100000),
                ]);
            }
        }
    }
}

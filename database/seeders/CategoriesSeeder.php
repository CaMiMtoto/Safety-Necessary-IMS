<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Raw Materials'],
            ['name' => 'Spare Parts'],
            ['name' => 'Tools & Equipment'],
            ['name' => 'Clothing & Apparel'],
            ['name' => 'Electronics & Appliances'],
            ['name' => 'Furniture & Fixtures'],
            ['name' => 'Medical Supplies'],
            ['name' => 'Automotive Parts'],
            ['name' => 'Construction Materials'],
        ];

        if (Category::count() > 0) {
            return;
        }

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => \Str::of($category['name'])->slug(). '-'. Str::random(10),
                'description' => $category['name'],
            ]);
        }

    }
}

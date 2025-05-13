<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = \App\Constants\Permission::all();
        foreach ($permissions as $permission) {
            $description = ucfirst($permission);
            $description = str_replace('_', ' ', $description);
            Permission::query()->updateOrCreate(['name' => $permission], ['description' => $description]);
        }
    }
}

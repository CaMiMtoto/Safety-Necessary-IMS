<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        User::query()->create([
            'name' => 'GEA Admin',
            'email' => 'admin@gear.rw',
            'password' => bcrypt('password'),
            'is_super_admin' => true,
            'phone_number' => '0780000000',
            'email_verified_at' => now(),
            'password_changed_at' => now(),
        ]);
    }
}

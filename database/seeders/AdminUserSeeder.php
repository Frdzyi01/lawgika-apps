<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin SPV (Super Admin)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin SPV',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '08111111111',
            ]
        );

        // 2. Admin 1 (Admin Order)
        User::updateOrCreate(
            ['email' => 'admin1@example.com'],
            [
                'name' => 'Admin Order 1',
                'password' => Hash::make('password'),
                'role' => 'admin1',
                'phone' => '08222222222',
            ]
        );

        // 3. Admin 2 (Admin Konten)
        User::updateOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name' => 'Admin Konten 2',
                'password' => Hash::make('password'),
                'role' => 'admin2',
                'phone' => '08333333333',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name'          => 'Budi Santoso',
                'email'         => 'budi@example.com',
                'password'      => Hash::make('password'),
                'role'          => 'customer',
                'phone'         => '081234567890',
                'company_name'  => 'PT Maju Terus',
                'business_type' => 'Teknologi Informasi',
                'address'       => 'Jl. Jend. Sudirman No. 1, Jakarta Pusat',
            ],
            [
                'name'          => 'Siti Aminah',
                'email'         => 'siti@example.com',
                'password'      => Hash::make('password'),
                'role'          => 'customer',
                'phone'         => '089876543210',
                'company_name'  => 'CV Karya Indah',
                'business_type' => 'Desain Grafis dan Percetakan',
                'address'       => 'Jl. Gatot Subroto No. 45, Bandung',
            ],
            [
                'name'          => 'Andi Wijaya',
                'email'         => 'andi@example.com',
                'password'      => Hash::make('password'),
                'role'          => 'customer',
                'phone'         => '085612349876',
                'company_name'  => 'Firma Hukum Wijaya & Rekan',
                'business_type' => 'Layanan Hukum',
                'address'       => 'Jl. MH Thamrin No. 10, Surabaya',
            ],
            [
                'name'          => 'Rina Melati',
                'email'         => 'rina@example.com',
                'password'      => Hash::make('password'),
                'role'          => 'customer',
                'phone'         => '082233445566',
                'company_name'  => 'Yayasan Pendidikan Nusantara',
                'business_type' => 'Pendidikan',
                'address'       => 'Jl. Merdeka No. 99, Yogyakarta',
            ],
            [
                'name'          => 'Joko Santoso',
                'email'         => 'joko@example.com',
                'password'      => Hash::make('password'),
                'role'          => 'customer',
                'phone'         => '081344556677',
                'company_name'  => 'PT Sinema Mandiri',
                'business_type' => 'Hiburan dan Media',
                'address'       => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
            ]
        ];

        foreach ($clients as $client) {
            User::updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}

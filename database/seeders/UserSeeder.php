<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => Hash::make('password'), // Ganti password sesuai kebutuhan
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'role' => 'manager',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff Gudang User',
                'email' => 'staffgudang@example.com',
                'role' => 'staff_gudang',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Supplier User',
                'email' => 'supplier@example.com',
                'role' => 'supplier',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

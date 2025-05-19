<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Suppliers;

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
                'password' => Hash::make('password123'), // Ganti password sesuai kebutuhan
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'role' => 'manager',
                'password' => Hash::make('manager123'),
            ],
            [
                'name' => 'Staff Gudang User',
                'email' => 'staffgudang@example.com',
                'role' => 'staff_gudang',
                'password' => Hash::make('staffgudang123'),
            ],
            [
                'name' => 'Supplier User',
                'email' => 'supplier@example.com',
                'role' => 'supplier',
                'password' => Hash::make('supplier123'),
            ],
        ];

        foreach ($users as $userData) {
           $user = User::create($userData);

           // If role is supplier, add to suppliers table
           if ($userData['role'] === 'supplier') {
               Suppliers::create([
                      'user_id' => $user->id,
                      'name' => $user->name,
                      'contact' => '08888888',
                      'email'  => $user->email,
                      'address'    => "Lorem Ipsum"
               ]);
           }
       }


    }
}

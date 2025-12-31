<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@techshop.com',
            'password' => Hash::make('password'),
            'phone' => '555-0100',
            'address' => '123 Admin Street',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country' => 'USA',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '555-0101',
            'address' => '456 Customer Ave',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90001',
            'country' => 'USA',
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}

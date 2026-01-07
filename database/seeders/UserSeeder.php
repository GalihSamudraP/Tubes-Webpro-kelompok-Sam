<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@twocoff.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_premium' => false,
        ]);

        // Barista
        User::create([
            'name' => 'Barista User',
            'email' => 'barista@twocoff.com',
            'password' => Hash::make('password'),
            'role' => 'barista',
            'is_premium' => false,
        ]);

        // Regular Client
        User::create([
            'name' => 'Regular Client',
            'email' => 'client@twocoff.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_premium' => false,
        ]);

        // Premium Client
        User::create([
            'name' => 'Premium Client',
            'email' => 'premium@twocoff.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_premium' => true,
        ]);
    }
}

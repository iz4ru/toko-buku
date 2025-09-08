<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'cashier',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'owner',
            'remember_token' => Str::random(10),
        ]);
    }
}

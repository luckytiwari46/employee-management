<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to avoid duplicates
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin@123'),
                'age' => 30,
                'role' => 'admin',
                'is_admin' => true, // important
            ]);
        }
    }
}

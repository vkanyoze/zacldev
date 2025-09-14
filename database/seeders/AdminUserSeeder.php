<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Demo admin user created successfully!');
        $this->command->warn('Email: admin@example.com');
        $this->command->warn('Password: password123');
    }
}

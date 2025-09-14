<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUsersSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create regular user if not exists
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_email_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Demo users are ready!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User: user@example.com / password');
    }
}

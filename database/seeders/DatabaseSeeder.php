<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\TestAdminSeeder;
use Database\Seeders\DemoUsersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a demo user with only the fields that exist in the schema
        $demoUser = [
            'id' => (string) Str::uuid(),
            'email' => 'demo@example.com',
            'password' => Hash::make('SecurePassword123!'),
            'email_verified_at' => now(),
            'is_email_verified' => true,
            'remember_token' => Str::random(10),
        ];

        // Remove any fields that don't exist in the database
        $columns = Schema::getColumnListing('users');
        $demoUser = array_intersect_key($demoUser, array_flip($columns));

        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            $demoUser
        );

        $this->call([
            AdminUserSeeder::class,
            TestAdminSeeder::class,
            DemoUsersSeeder::class,
            DemoDataSeeder::class,
            // Add other seeders here
        ]);
    }
}

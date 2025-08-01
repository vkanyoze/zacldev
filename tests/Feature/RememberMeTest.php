<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RememberMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_remember_me_functionality_works()
    {
        // Create a test user
        $user = User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_email_verified' => 1
        ]);

        // Test login with remember me checked
        $response = $this->post('/custom-login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => 'on'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Check that remember token was set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    public function test_remember_me_functionality_without_checkbox()
    {
        // Create a test user
        $user = User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_email_verified' => 1
        ]);

        // Test login without remember me checked
        $response = $this->post('/custom-login', [
            'email' => 'test@example.com',
            'password' => 'password123'
            // No remember field
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Check that remember token was not set
        $user->refresh();
        $this->assertNull($user->remember_token);
    }
} 
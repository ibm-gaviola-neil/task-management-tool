<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_required_fields_validation(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_invalid_email_validation(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => '12345678',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'email' => ['The email field must be a valid email address.'],
            ]
        ]);
    }

    public function test_min_password_character_validation(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@sample.com',
            'password' => '1234',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'password' => ['The password must be at least 8 characters.'],
            ]
        ]);
    }

    public function test_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@sample.com',
            'password' => '123456789',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
            'status' => 401,
        ]);
    }

    public function test_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'alice@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
           'status' => 200,
            'message' => 'Login successfully',
        ]);
    }

    public function test_401_access_user()
    {
        $response = $this->getJson('/api/auth/user');
        $response->assertStatus(401); 
    }

    public function test_get_authenticated_user_authenticated()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/auth/user');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_logout_function()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->actingAs($user);
    
        $response = $this->post('/api/auth/logout');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Logged out successfully',
        ]);
    }
}

<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Services\AuthenticationService;
use App\Models\User;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_invalid_credentials_login(): void
    {
        $payload = [
            'email' => 'invalid@gmail.com',
            'password' => 'invalidpassword'
        ];

        $authService = new AuthenticationService();

        $response = $authService->login($payload);
        $this->assertEquals(401, $response['status']);
        $this->assertEquals('Invalid credentials', $response['message']);
    }

    public function test_valid_credentials_login(): void
    {
        $payload = [
            'email' => 'alice@example.com',
            'password' => 'password123'
        ];

        User::factory()->create([
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $authService = new AuthenticationService();

        $response = $authService->login($payload);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Login successfully', $response['message']);
    }
}

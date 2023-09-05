<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithFaker;
    public function testSuccessLogin(): void
    {
        $user = User::factory()->create();
        $email = $user->email;
        $response = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }




    public function testInfoApi(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/api/auth/info', );

        $response->assertStatus(200);
    }

    public function testWrongPassword(): void
    {
        $user = User::factory()->create();
        $email = $user->email;
        $response = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => 'password22',
        ]);

        $response->assertStatus(401);
    }
    public function testUserNotActivated(): void
    {
        $user = User::factory()->unverified()->create();
        $email = $user->email;
        $response = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}

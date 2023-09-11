<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BaseCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected string $accessToken = '';

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->artisan('db:seed');

        $this->login();

        $this->withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ]);

        $this->actingAs($this->user);
    }

    protected function login(): void
    {
        $responseRegister = $this->postJson('/api/auth/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!',
        ]);

        $this->accessToken = $responseRegister->json('data.access_token');
        $this->user = (new User())->find($responseRegister->json('data.id'));

    }
}

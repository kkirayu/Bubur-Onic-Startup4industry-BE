<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * Test Register If Input Not Filled
     */
    public function test_register_if_input_not_filled()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
            ]);
    }

    /**
     * Test Register If Email Already Registered
     */
    public function test_register_if_email_already_registered()
    {
        $email = $this->faker->unique()->safeEmail;

        $this->postJson('/api/auth/register', [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    /**
     * Test Register If Password Not Match
     */
    public function test_register_if_password_not_match()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password',
            ]);
    }

    /**
     * Test Register If Success
     */
    public function test_register_if_success()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'access_token',
                ],
            ]);
    }

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
        $response = $this->get('/api/auth/info', );

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

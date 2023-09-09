<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_forgot_password_send_email_not_found()
    {
        $response = $this->postJson('/api/auth/password/email', [
            'email' => $this->faker()->email(),
            'type' => 'api'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email'
            ]);
    }

    public function test_forgot_password_send_email_success()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/auth/password/email', [
            'email' => $user->email,
            'type' => 'api'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_confirm_password_user_not_login()
    {
        $response = $this->postJson('/api/auth/password/confirm', [
            'current_password' => 'asdf1234',
            'new_password' => 'asdf1234',
            'new_password_confirmation' => 'asdf1234'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_confirm_password_current_password_not_match()
    {
        $this->createUser();
        $response = $this->postJson('/api/auth/password/confirm', [
            'current_password' => 'asdf1234',
            'new_password' => 'Asdf1234!',
            'new_password_confirmation' => 'Asdf1234!'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);
    }


    public function test_confirm_password_new_password_confirmation_does_not_match()
    {

        $this->createUser();
        $response = $this->postJson('/api/auth/password/confirm', [
            'current_password' => 'asdf1234',
            'new_password' => 'Asdf1234!',
            'new_password_confirmation' => 'Asdf1234!1'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'new_password'
            ]);
    }

    public function test_confirm_password_success()
    {
        $this->createUser();
        $response = $this->postJson('/api/auth/password/confirm', [
            'current_password' => 'password',
            'new_password' => 'Asdf1234!',
            'new_password_confirmation' => 'Asdf1234!'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_reset_password_empty_fields()
    {
        // $this->test_forgot_password_send_email_success();
        // $user = User::query()->first();
        // $token = PasswordResetToken::query()->where('email', $user->email)->first('token');
        $response = $this->postJson('/api/auth/password/reset');

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
                'token',
                'password',
            ]);
    }

    public function test_reset_password_email_not_valid()
    {
        $response = $this->postJson('/api/auth/password/reset', [
            'token' => '123',
            'email' => 'hello@example.com',
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    public function test_reset_password_success()
    {
        $user = User::factory()->create();
        $token = \Password::broker()->createToken($user);

        $response = $this->postJson('/api/auth/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Asdf1234!',
            'password_confirmation' => 'Asdf1234!'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'message',
            ]);
    }

    private function createUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }
}
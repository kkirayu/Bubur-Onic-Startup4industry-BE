<?php

namespace Tests\Feature\Crud;

use Tests\BaseCrudTest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserTest extends BaseCrudTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_show_list_success()
    {
        $response = $this->getJson('/api/crud/user');

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function test_scope_user_not_active()
    {
        User::factory()->count(5)->create();
        
        $response = $this->getJson('/api/crud/user/scope/NotActive');

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }

    public function test_detail_not_found()
    {
        $response = $this->getJson('/api/crud/user/1000');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'status',
                'message'
            ]);
    }

    public function test_detail_success()
    {
        $id = auth()->user()->id;
        $response = $this->getJson('/api/crud/user/' . $id);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'data',
                'message',
                'status'
            ]);
    }

    public function test_create_empty_field()
    {
        $response = $this->postJson('/api/crud/user', []);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password'
            ]);
    }

    public function test_create_email_exists()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/crud/user', [
            'email' => $user->email,
            'name' => $this->faker->name,
            'password' => 'Asdf1234!'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_create_success()
    {
        $response = $this->postJson('/api/crud/user', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ]);

        $response->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJsonStructure([
                'data',
                'status',
                'message'
            ]);
    }

    public function test_update_empty_field()
    {
        $id = auth()->user()->id;
        $response = $this->postJson('/api/crud/user/' . $id, []);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password'
            ]);
    }

    public function test_update_id_not_foun()
    {
        $response = $this->postJson('/api/crud/user/1000', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_update_success()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/crud/user/' . $user->id, [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => $this->faker->password
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ],
                'status',
                'message'
            ]);
    }

    public function test_delete_user_not_found()
    {
        $response = $this->deleteJson('/api/crud/user/1000');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_delete_user_success()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson('/api/crud/user/' . $user->id);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'status',
                'data'
            ]);
    }
}
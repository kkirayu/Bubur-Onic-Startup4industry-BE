<?php

namespace Tests\Feature\Crud;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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


    private function createUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }
}

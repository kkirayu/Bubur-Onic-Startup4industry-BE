<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $user = User::factory()->create();
        $deleteUser = User::factory()->create();
        


        $this->actingAs($user);
        $response = $this->deleteJson('/api/crud/user/' . $deleteUser->id , [
            "user_deleted_reason" => "test"
        ] );

        $this->assertDatabaseHas('users', [
            'id' => $deleteUser->id,
            'user_deleted_reason' => "test"
        ]);
        

        $response->assertStatus(200);
    }
}

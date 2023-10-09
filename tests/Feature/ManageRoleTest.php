<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageRoleTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use WithFaker;
    public function test_example(): void
    {

        $user =  User::factory()->create();
        $this->actingAs($user);


        $response = $this->get('/api/crud/role');
        

        $response->assertStatus(200);
    }
    
    public function testDetailRole(): void
    {

        $user =  User::factory()->create();
        $this->actingAs($user);


        $role  = Role::create(["name" => $this->faker->name]);
        $response = $this->get('/api/crud/role/' . $role->id, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        $response->assertStatus(200);
    }
}

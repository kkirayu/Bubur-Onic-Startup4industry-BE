<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AddPermissionToRoleTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user =  User::factory()->create();

        $this->actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name,
        ]);
        Permission::create([
            'name' => $this->faker->name,
        ]);
        Permission::create([
            'name' => $this->faker->name,
        ]);
        
        $permission = Permission::limit(2)->get()->pluck('id')->toArray();

        dump('/api/crud/role/' . $role->id . '/permissions');
        
        dump(json_encode($permission));
        
        $response = $this->postJson('/api/crud/role/' . $role->id . '/permissions', [
            'permissions' => $permission
        ]);
        $response->assertStatus(200);

    }
}

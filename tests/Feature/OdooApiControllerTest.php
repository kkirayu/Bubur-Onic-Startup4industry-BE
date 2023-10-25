<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OdooApiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        // 
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $payload = [
            "model" => "res.partner",
            "method" => "name_create",
            "args" => [

                "Javan 100"

            ],
            "kwargs" => []
        ];
        $response = $this->postJson('/api/odoo/odoo-api', $payload);
        dump(json_encode($payload));
        dd($response->json());
        

        $response->assertStatus(200);
    }
}

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
            "params" => [

                "Javan 100"

            ],
            "kwarg" => []
        ];
        $response = $this->postJson('/api/odoo/odoo-api', $payload);
        dd($response->json());

        $response->assertStatus(200);
    }
}

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
            
            "kwargs" => [], 
            "res_type" => "STATEMENT",
        ];
        $response = $this->postJson('/api/odoo/odoo-api', $payload);
        dump(json_encode($payload));
        dd($response->json());
        

        $response->assertStatus(200);
    }
    public function testPginated(): void
    {

        // 
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $payload = [
            "model" => "account.asset.asset",
            "method" => "web_search_read",
            "args" => [],
            "kwargs" => [], 
            "res_type" => "PAGINATEDLIST",
        ];
        $response = $this->postJson('/api/odoo/odoo-api', $payload);
        dump(json_encode($payload));
        dd($response->json());
        

        $response->assertStatus(200);
    }
    public function testGetRaw(): void
    {

        // 
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $payload = [
            "model" => "res.partner",
            "method" => "search",
            "args" => [[['is_company', '=', True]]],
            "kwargs" => [], 
            "res_type" => "RAWLIST",
        ];
        $response = $this->postJson('/api/odoo/odoo-api', $payload);
        dump(json_encode($payload));
        dd($response->json());
        

        $response->assertStatus(200);
    }
}

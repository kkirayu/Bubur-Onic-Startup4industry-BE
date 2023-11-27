<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanPerubahanModalControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_example(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-perubahan-modal?company=1&start=01/01/2021&end=30/11/2023");

        dump(json_encode($response->json() ,  JSON_PRETTY_PRINT));
        $response->assertStatus(200);
    }
}

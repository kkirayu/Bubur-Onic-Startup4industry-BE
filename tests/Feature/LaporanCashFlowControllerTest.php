<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanCashFlowControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_example(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-cash-flow?company=1&coa=30012312&start=01/01/2023&end=01/12/2023");

        dump(json_encode($response->json() ,  JSON_PRETTY_PRINT));
        $response->assertStatus(200);
    }
}

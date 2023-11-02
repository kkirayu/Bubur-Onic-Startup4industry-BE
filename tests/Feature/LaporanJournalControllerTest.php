<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanJournalControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-journal?company=1&type=all&start=01/10/2023&end=30/10/2023");
        dd($response->json());
        $response->assertStatus(200);
    }
    public function test_type_kas(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-journal?company=1&type=kas&start=01/10/2023&end=15/10/2023");
        dd($response->json());
        $response->assertStatus(200);
    }
    public function test_type_non_kas(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-journal?company=1&type=non-kas&start=01/10/2023&end=15/10/2023");

        dd($response->json());
        $response->assertStatus(200);
    }
}

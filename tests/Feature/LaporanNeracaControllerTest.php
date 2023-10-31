<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanNeracaControllerTest extends TestCase
{

    public function test_example(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/laporan/laporan-neraca?company=1&start=01/10/2023&end=31/10/2023");
        dump("/api/laporan/laporan-neraca?company=1&start=01/09/2023&end=31/10/2023");

        dump(json_encode($response->json()));
        $response->assertStatus(200);
    }
}

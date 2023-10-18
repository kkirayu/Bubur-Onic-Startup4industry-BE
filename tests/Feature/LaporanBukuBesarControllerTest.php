<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanBukuBesarControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    
     public function test_example(): void
     {
         $user = UserFactory::new()->create();
         $this->actingAs($user);
         dump("/api/laporan/laporan-buku-besar?company=1&group=asset_receivable&start=01/10/2023&end=18/10/2023");
         $response = $this->getJson("/api/laporan/laporan-buku-besar?company=1&group=asset_receivable&start=01/10/2023&end=18/10/2023");
 
         dump(json_encode($response->json()));
         $response->assertStatus(200);
     }
     
}


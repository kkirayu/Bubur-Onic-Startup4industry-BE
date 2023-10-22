<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanLabaRugiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     public function test_example(): void
     {
         $user = UserFactory::new()->create();
         $this->actingAs($user);
         $response = $this->getJson("/api/laporan/laporan-laba-rugi?company=1&date=18/10/2023");
         dump("/api/laporan/laporan-laba-rugi?company=1&date=18/10/2023");
 
         dump(json_encode($response->json()));
         $response->assertStatus(200);
     }
     public function testDownloadPdf(): void
     {
         $user = UserFactory::new()->create();
         $this->actingAs($user);
         $response = $this->getJson("/api/laporan/laporan-laba-rugi/export?company=1&date=18/10/2023");
         dump("/api/laporan/laporan-laba-rugi/export?type=pdf&company=1&date=18/10/2023");
 
        //  dump(json_encode($response->json()));

         $response->assertStatus(200);
     }
}

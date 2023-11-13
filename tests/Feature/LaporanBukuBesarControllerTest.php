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
         dump("/api/laporan/laporan-buku-besar?company=1&group=11&coa=30012312&group=30/11/2023&start=01/10/2023&end=30/12/2023");
         $response = $this->getJson("/api/laporan/laporan-buku-besar?company=1&group=11&coa=30012312&start=01/01/2023&end=01/12/2023");
 
         dump(json_encode($response->json()));
         $response->assertStatus(200);

         
     }
     
}


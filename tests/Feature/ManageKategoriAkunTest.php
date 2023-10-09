<?php

namespace Tests\Feature;

use App\Models\KategoriAkun;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageKategoriAkunTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use WithFaker;
    public function test_example(): void
    {

        $user =  User::factory()->create();
        $this->actingAs($user);


        $response = $this->get('/api/akun/kategori-akun');
        

        $response->assertStatus(200);
    }
    
    public function testDetailRole(): void
    {

        $user =  User::factory()->create();
        $this->actingAs($user);


        $role  = KategoriAkun::create(["nama" => $this->faker->name, "deskripsi" => $this->faker->name, "perusahaan_id" => 1, "cabang_id" => 1]);
        dump('/api/akun/kategori-akun/' . $role->id);
        $response = $this->get('/api/akun/kategori-akun/' . $role->id, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        $response->assertStatus(200);
    }
}

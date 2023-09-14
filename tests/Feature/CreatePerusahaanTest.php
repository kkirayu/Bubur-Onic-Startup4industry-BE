<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreatePerusahaanTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use WithFaker;

    
    public function createPerusahaanWithValidData(): void
    {
        // create payload from  CreatePeruysahaanRequest
        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ],
            "owner" => [
                "nama" => $this->faker->name,
                "email" => $this->faker->email,
                "password" => "password"
            ]
        ];


        $user = \App\Models\User::factory()->create();
        // $this->actingAs($user);
        
        

        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $perusahaan = DB::table('perusahaans')->where('nama', $payload['nama'])->first();
        $this->assertNotNull($perusahaan);
        $cabang = DB::table('cabangs')->where('nama', $payload['cabang']['nama'])->first();
        $this->assertNotNull($cabang);
        $owner = DB::table('users')->where('name', $payload['owner']['nama'])->first();
        $this->assertNotNull($owner);
        


        $response->assertStatus(201);
    }
    public function testCreatePerusahaanWithExsistingPerusahaanName(): void
    {
        // create payload from  CreatePeruysahaanRequest
        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ],
            "owner" => [
                "nama" => $this->faker->name,
                "email" => $this->faker->email,
                "password" => "password"
            ]
        ];


        $user = \App\Models\User::factory()->create();
        // $this->actingAs($user);
        
        

        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);


        $response->assertStatus(201);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);


        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'nama',
            ]);

    }
    public function testValidateOwnerNotSend(): void
    {
        // create payload from  CreatePeruysahaanRequest
        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ]
        ];


        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);


        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'owner.nama',
                'owner.email',
                'owner.password',
            ]);

    }
}
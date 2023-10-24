<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AkunControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/akun/akun?company=1&date=18/10/2023");

        dump($response->json());

        $response->assertStatus(200);
    }
    public function test_create_akun(): void
    {

        $payload = [
            "kategori_akun" => 12,
            "is_akun_bank" => true,
            "kode_akun" => "103123122",
            "nama_akun" => "12321321",
            "account_type" => "asset_cash",
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ];
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson("/api/akun/akun/create-akun", $payload);

        dump($response->json());

        $response->assertStatus(200);
    }
}

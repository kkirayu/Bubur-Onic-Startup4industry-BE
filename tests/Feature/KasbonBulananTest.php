<?php

namespace Tests\Feature;

use App\Models\KasbonBulanan;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KasbonBulananTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testListKasbon(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->get('/api/pegawai/kasbon-bulanan');

        dump($response->getContent());
        $response->assertStatus(200);
    }
    public function testListKasbonFilterByBulanDanTahun(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->get("/api/pegawai/kasbon-bulanan?filter[bulan]=2&filter[tahun]=2023");

        dump($response->getContent());
        $response->assertStatus(200);
    }


    public function testCreateKasbonBulanan(): void
    {

        KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1995)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $payload = [
            "bulan" => "2",
            "tahun" => "1995",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
        ];
        $response = $this->postJson("/api/pegawai/kasbon-bulanan", $payload);

        dump($response->getContent());

        $response->assertStatus(201);
    }


    public function testCreateKasabonBulanan2Kali(): void
    {

        KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1995)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $payload = [
            "bulan" => "2",
            "tahun" => "1995",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
        ];
        $response = $this->postJson("/api/pegawai/kasbon-bulanan", $payload);

        $response->assertStatus(201);
        $payload = [
            "bulan" => "2",
            "tahun" => "1995",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
        ];
        $response = $this->postJson("/api/pegawai/kasbon-bulanan", $payload);

        $response->assertStatus(422);
        $response->assertJson(["message"=> "Sudah Memiliki Kasbon di periode tersebut"]);
        dump($response->getContent());

    }


    public function testDeleteKasbonNew(): void
    {

        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1995)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1996",
            "status"=> "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->deleteJson("/api/pegawai/kasbon-bulanan/" .  $kasbon->id);

        dump($response->getContent());

        $response->assertStatus(200);
    }



    public function testDeleteKasbonNotNew(): void
    {

        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1995)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1996",
            "status"=> "POSTING",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->deleteJson("/api/pegawai/kasbon-bulanan/" .  $kasbon->id);


        dump($response->getContent());

        $response->assertStatus(422);
        $response->assertJson(["message"=> "Data Dengan Status POSTING Tidak bisa di hapus"]);
    }

    public function testUpdateKasbonStatus(): void
    {
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1996",
            "status" => "POSTING",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);

        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $payload = [
            'status' => 'CAIR',
        ];

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/{$kasbon->id}/update_status", $payload);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $kasbon->id,
                'status' => 'CAIR',
            ],
        ]);

        $this->assertDatabaseHas('kasbon_bulanans', [
            'id' => $kasbon->id,
            'status' => 'CAIR',
        ]);

        $kasbon->status = 'CAIR';
        $kasbon->save();

        $payload = [
            'status' => 'CAIR',
        ];

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/{$kasbon->id}/update_status", $payload);

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'Invalid status update',
        ]);

        $this->assertDatabaseHas('kasbon_bulanans', [
            'id' => $kasbon->id,
            'status' => 'CAIR',
        ]);
    }




}

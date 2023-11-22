<?php

namespace Tests\Feature;

use App\Models\KasbonBulanan;
use App\Models\ProfilPegawai;
use App\Models\Team;
use App\Models\VerifikasiKasbonBulanan;
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
        $response = $this->getJson('/api/pegawai/kasbon-bulanan');

        $response->assertStatus(200);
    }

    public function testListKasbonFilterByBulanDanTahun(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/pegawai/kasbon-bulanan?filter[bulan]=2&filter[tahun]=2023");

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


        $response->assertStatus(201);
    }


    public function testAmbilKasbonKaryawan(): void
    {

        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1997)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->first();
        VerifikasiKasbonBulanan::where("kasbon_bulanan_id" , $kasbon->id)->delete();


        $kasbon =$kasbon->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        ProfilPegawai::create([
            'user_id' => $user->id,
            'kode_pegawai' => "PGW" . date('YmdHis') . rand(100, 999),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            'alamat' => 'Jl. Sudirman No. 123',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => null,
            'status_kawin' => true,
            'nomor_ktp' => '1234567890',
            'npwp' => '1234567890',
            'gaji_pokok' => 5000000,
            'uang_hadir' => 500000,
            'tunjangan_jabatan' => 1000000,
            'tunjangan_tambahan' => 500000,
            'extra_rajin' => 500000,
            'thr' => 1000000,
            'tunjangan_lembur' => 500000,
            'quota_cuti_tahunan' => 12,
            'team_id' => $team->id,
        ]);
        $this->actingAs($user);

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/$kasbon->id/ambil-kasbon");



        $response->assertStatus(201);
    }


    public function testBatalkanKasbonYangDiAmbil(): void
    {
        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1997)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->first();
        VerifikasiKasbonBulanan::where("kasbon_bulanan_id" , $kasbon->id)->delete();


        $kasbon =$kasbon->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilePegawai = ProfilPegawai::create([
            'user_id' => $user->id,
            'kode_pegawai' => "PGW" . date('YmdHis') . rand(100, 999),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            'alamat' => 'Jl. Sudirman No. 123',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => null,
            'status_kawin' => true,
            'nomor_ktp' => '1234567890',
            'npwp' => '1234567890',
            'gaji_pokok' => 5000000,
            'uang_hadir' => 500000,
            'tunjangan_jabatan' => 1000000,
            'tunjangan_tambahan' => 500000,
            'extra_rajin' => 500000,
            'thr' => 1000000,
            'tunjangan_lembur' => 500000,
            'quota_cuti_tahunan' => 12,
            'team_id' => $team->id,
        ]);
        $this->actingAs($user);

        VerifikasiKasbonBulanan::create([
            "profile_pegawai_id" => $profilePegawai->id,
            "kasbon_bulanan_id" => $kasbon->id,
            "status" => "DIAJUKAN",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
            "total_kasbon" => $profilePegawai->team->quota_kasbon_bulanan,
        ]);

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/$kasbon->id/batal-kasbon");



        $response->assertStatus(200);
    }

    public function testTidakBisaBatalkanKasbonYangSudahBatal(): void
    {
        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1997)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->first();
        VerifikasiKasbonBulanan::where("kasbon_bulanan_id" , $kasbon->id)->delete();


        $kasbon =$kasbon->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilePegawai = ProfilPegawai::create([
            'user_id' => $user->id,
            'kode_pegawai' => "PGW" . date('YmdHis') . rand(100, 999),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            'alamat' => 'Jl. Sudirman No. 123',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => null,
            'status_kawin' => true,
            'nomor_ktp' => '1234567890',
            'npwp' => '1234567890',
            'gaji_pokok' => 5000000,
            'uang_hadir' => 500000,
            'tunjangan_jabatan' => 1000000,
            'tunjangan_tambahan' => 500000,
            'extra_rajin' => 500000,
            'thr' => 1000000,
            'tunjangan_lembur' => 500000,
            'quota_cuti_tahunan' => 12,
            'team_id' => $team->id,
        ]);
        $this->actingAs($user);

        VerifikasiKasbonBulanan::create([
            "profile_pegawai_id" => $profilePegawai->id,
            "kasbon_bulanan_id" => $kasbon->id,
            "status" => "DIBATALKAN",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
            "total_kasbon" => $profilePegawai->team->quota_kasbon_bulanan,
        ]);

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/$kasbon->id/batal-kasbon");



        $response->assertStatus(422);
    }

    public function testAmbilKasbonYangSudahDibatalkan(): void
    {
        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1997)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->first();
        VerifikasiKasbonBulanan::where("kasbon_bulanan_id" , $kasbon->id)->delete();


        $kasbon =$kasbon->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilePegawai = ProfilPegawai::create([
            'user_id' => $user->id,
            'kode_pegawai' => "PGW" . date('YmdHis') . rand(100, 999),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            'alamat' => 'Jl. Sudirman No. 123',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => null,
            'status_kawin' => true,
            'nomor_ktp' => '1234567890',
            'npwp' => '1234567890',
            'gaji_pokok' => 5000000,
            'uang_hadir' => 500000,
            'tunjangan_jabatan' => 1000000,
            'tunjangan_tambahan' => 500000,
            'extra_rajin' => 500000,
            'thr' => 1000000,
            'tunjangan_lembur' => 500000,
            'quota_cuti_tahunan' => 12,
            'team_id' => $team->id,
        ]);
        $this->actingAs($user);

        VerifikasiKasbonBulanan::create([
            "profile_pegawai_id" => $profilePegawai->id,
            "kasbon_bulanan_id" => $kasbon->id,
            "status" => "DIBATALKAN",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
            "total_kasbon" => $profilePegawai->team->quota_kasbon_bulanan,
        ]);

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/$kasbon->id/ambil-kasbon");



        $response->assertStatus(200);
    }


    public function testGagalAmbilKasbonKarenaSudahAmbil(): void
    {
        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1997)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->first();
        VerifikasiKasbonBulanan::where("kasbon_bulanan_id" , $kasbon->id)->delete();


        $kasbon =$kasbon->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1997",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilePegawai = ProfilPegawai::create([
            'user_id' => $user->id,
            'kode_pegawai' => "PGW" . date('YmdHis') . rand(100, 999),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            'alamat' => 'Jl. Sudirman No. 123',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => null,
            'status_kawin' => true,
            'nomor_ktp' => '1234567890',
            'npwp' => '1234567890',
            'gaji_pokok' => 5000000,
            'uang_hadir' => 500000,
            'tunjangan_jabatan' => 1000000,
            'tunjangan_tambahan' => 500000,
            'extra_rajin' => 500000,
            'thr' => 1000000,
            'tunjangan_lembur' => 500000,
            'quota_cuti_tahunan' => 12,
            'team_id' => $team->id,
        ]);
        $this->actingAs($user);

        VerifikasiKasbonBulanan::create([
            "profile_pegawai_id" => $profilePegawai->id,
            "kasbon_bulanan_id" => $kasbon->id,
            "status" => "DIAJUKAN",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
            "total_kasbon" => $profilePegawai->team->quota_kasbon_bulanan,
        ]);

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/$kasbon->id/ambil-kasbon");



        $response->assertStatus(422);
    }



    public function testCreateKasabonBulanan2Kali(): void
    {

        KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1995)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();

        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1995",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);

        $payload = [
            "bulan" => "2",
            "tahun" => "1995",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
        ];
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson("/api/pegawai/kasbon-bulanan", $payload);

        $response->assertStatus(422);
        $response->assertJson(["message" => "Sudah Membuat Kasbon periode tersebut"]);

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
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->deleteJson("/api/pegawai/kasbon-bulanan/" . $kasbon->id);


        $response->assertStatus(200);
    }


    public function testDeleteKasbonNotNew(): void
    {

        $kasbon = KasbonBulanan::where("bulan", 2)
            ->where("tahun", 1996)
            ->where("perusahaan_id", 1)
            ->where("cabang_id", 1)->delete();
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
        $response = $this->deleteJson("/api/pegawai/kasbon-bulanan/" . $kasbon->id);



        $response->assertStatus(422);
        $response->assertJson(["message" => "Data Dengan Status POSTING Tidak bisa di hapus"]);
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

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'status' => [
                    'Status Kasbon Tidak Valid',
                ],
            ],
        ]);

        $this->assertDatabaseHas('kasbon_bulanans', [
            'id' => $kasbon->id,
            'status' => 'CAIR',
        ]);
    }


    public function testUpdateKasbon(): void
    {
        $kasbon = KasbonBulanan::where(

            "bulan" ,  "2",
        )->where(

            "status",  "POSTING",
        )->where(

            "tahun",  "2000",
        )->delete();
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "2000",
            "status" => "POSTING",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);

        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $payload = [
            'status' => 'CAIR',
            'bulan' => '2',
            'tahun' => '1996',
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
        ];

        $response = $this->postJson("/api/pegawai/kasbon-bulanan/{$kasbon->id}" , $payload);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $kasbon->id,
                'bulan' => '2',
                'tahun' => '1996',
            ],
        ]);

    }
    public function testUpdateStatusFromNewToPosting()
    {
        $kasbon = KasbonBulanan::create([
            "bulan" => "2",
            "tahun" => "1996",
            "status" => "NEW",
            "tanggal_pencairan" => Carbon::now()->format("Y-m-d"),
            "perusahaan_id" => 1,
            "cabang_id" => 1
        ]);

        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $response = $this->postJson( '/api/pegawai/kasbon-bulanan/'.$kasbon->id.'/update_status', ['status' => 'POSTING']);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Status berhasil diperbarui',
                 ]);

        $this->assertDatabaseHas('kasbon_bulanans', ['id' => $kasbon->id, 'status' => 'POSTING']);
    }

    public function testUpdateStatusFromPostingToCair()
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

        $response = $this->postJson( '/api/pegawai/kasbon-bulanan/'.$kasbon->id.'/update_status', ['status' => 'CAIR']);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Status berhasil diperbarui',
                 ]);

        $this->assertDatabaseHas('kasbon_bulanans', ['id' => $kasbon->id, 'status' => 'CAIR']);
    }

    public function testInvalidStatusUpdate()
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

        $response = $this->json('POST', '/api/pegawai/kasbon-bulanan/'.$kasbon->id.'/update_status', ['status' => 'NEW']);

        $response->assertStatus(422)
             ->assertJson([
                 'message' => 'The selected status is invalid.',
             ]);

        $this->assertDatabaseHas('kasbon_bulanans', ['id' => $kasbon->id, 'status' => 'POSTING']);
    }

}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\ProfilPegawai;
use Illuminate\Support\Carbon;
use App\Models\PotonganKaryawan;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PotonganKaryawanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testListPotongan(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson('api/potongan-karyawan/potongan-karyawan');

        $response->assertStatus(200);
    }

    public function testListPotonganFilterByBulanDanTahun(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->getJson("/api/potongan-karyawan/potongan-karyawan?filter[bulan]=2&filter[tahun]=2023");

        $response->assertStatus(200);
    }

    public function testcreatePotonganKaryawan()
    {
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilPegawai = ProfilPegawai::create([
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

        $response = $this->postJson('api/potongan-karyawan/potongan-karyawan', [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_potongan' => 5000.00,
            'alasan_potongan' => 'Tidak Achievement',
            'bukti_potongan' => 'tes.jpg',
            'status' => 'BARU',
            'total_diambil' => '100.00',
        ]);

        $response->assertStatus(201);

    }

    public function testPotonganKaryawanWithNoIdProfilePegawai()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/potongan-karyawan/potongan-karyawan', [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => 999,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'total_diambil' => 'taken',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdatePotonganKaryawanDIAJUKAN()
    {
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilPegawai = ProfilPegawai::create([
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

        $potongan = PotonganKaryawan::create([
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_potongan' => 5000.00,
            'alasan_potongan' => 'Tidak Achievement',
            'bukti_potongan' => 'tes.jpg',
            'status' => 'BARU',
            'total_diambil' => '100.00',
            'perusahaan_id' => '1',
            'cabang_id' => '1'
        ]);

        $response = $this->postJson("api/potongan-karyawan/potongan-karyawan/{$potongan->id}", [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_potongan' => 5000.00,
            'alasan_potongan' => 'Tidak Achievement',
            'bukti_potongan' => 'tes.jpg',
            'status' => 'DIAJUKAN',
            'total_diambil' => '100.00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('potongan_karyawans', [
            'id' => $potongan->id,
            'status' => 'DIAJUKAN',
        ]);
    }

    public function testUpdatePotonganKaryawanDIBATALKAN()
    {
        $user = UserFactory::new()->create();
        $team = Team::create([
            "nama" => "TestTeam",
            "deskripsi" => "TestTeam",
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            "quota_kasbon_bulanan" => 10000,
        ]);
        $profilPegawai = ProfilPegawai::create([
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

        $potongan = PotonganKaryawan::create([
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_potongan' => 5000.00,
            'alasan_potongan' => 'Tidak Achievement',
            'bukti_potongan' => 'tes.jpg',
            'status' => 'BARU',
            'total_diambil' => '100.00',
            'perusahaan_id' => '1',
            'cabang_id' => '1'
        ]);

        $response = $this->postJson("api/potongan-karyawan/potongan-karyawan/{$potongan->id}", [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_potongan' => 5000.00,
            'alasan_potongan' => 'Tidak Achievement',
            'bukti_potongan' => 'tes.jpg',
            'status' => 'DIBATALKAN',
            'total_diambil' => '100.00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('potongan_karyawans', [
            'id' => $potongan->id,
            'status' => 'DIBATALKAN',
        ]);
    }
}

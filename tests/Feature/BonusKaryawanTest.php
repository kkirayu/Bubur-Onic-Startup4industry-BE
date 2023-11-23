<?php
use Tests\TestCase;
use App\Models\Team;
use App\Models\BonusKaryawan;
use App\Models\ProfilPegawai;
use Database\Factories\UserFactory;

class BonusKaryawanTest extends TestCase
{
    /** @test */
    public function createBonusKaryawan()
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

        $response = $this->postJson('api/bonus-karyawan/bonus-karyawan', [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profilPegawai->id,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'total_diambil' => 'taken',
        ]);

        $response->assertStatus(201);

    }

    /** @test */
    public function createBonusKaryawanWithNoIdProdilePegawai()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/bonus-karyawan/bonus-karyawan', [
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

    /** @test */
    public function updateStatusBonusDIAJUKAN()
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

        $bonus = BonusKaryawan::create([
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'BARU',
            'total_diambil' => 'taken',
        ]);

        $response = $this->postJson(  "api/bonus-karyawan/bonus-karyawan/{$bonus->id}/update_status", [
            'status' => 'DIAJUKAN',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bonus_karyawans', [
            'id' => $bonus->id,
            'status' => 'DIAJUKAN',
        ]);
    }

    /** @test */
    public function updateStatusBonusDIBATALKAN()
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

        $bonus = BonusKaryawan::create([
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'BARU',
            'total_diambil' => 'taken',
        ]);

        $response = $this->postJson( "api/bonus-karyawan/bonus-karyawan/{$bonus->id}/update_status", [
            'status' => 'DIBATALKAN',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bonus_karyawans', [
            'id' => $bonus->id,
            'status' => 'DIBATALKAN',
        ]);
    }

    public function testUpdateStatusBonusBukanBARU()
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

        $bonus = BonusKaryawan::create([
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'DIBATALKAN',
            'total_diambil' => 'taken',
        ]);

        $response = $this->postJson( "api/bonus-karyawan/bonus-karyawan/{$bonus->id}/update_status", [
            'status' => 'DIBATALKAN',
        ]);

        $response->assertStatus(422);
    }


    public function testUpdateBonusKaryawan()
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

        $bonus = BonusKaryawan::create([
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'BARU',
            'total_diambil' => 'taken',
        ]);

        $response = $this->postJson("api/bonus-karyawan/bonus-karyawan/{$bonus->id}", [
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus di edit',
            'bukti_bonus' => 'proof123',
            'status' => 'BARU',
            'total_diambil' => 'taken',
        ]);

        $response->assertStatus(200);
    }

    public function testHapusBonusKaryawan()
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

        $bonus = BonusKaryawan::create([
            'id_profile_pegawai' => $profilPegawai->id,
            'bulan' => 11,
            'tahun' => 2023,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'BARU',
            'total_diambil' => 'taken',
        ]);

        $response = $this->deleteJson( "api/bonus-karyawan/bonus-karyawan/{$bonus->id}");

        $response->assertStatus(200);
    }
}

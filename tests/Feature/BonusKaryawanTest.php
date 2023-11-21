<?php
use Tests\TestCase;
use App\Models\ProfilPegawai;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BonusKaryawanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_bonus_karyawan()
    {
        $profile = ProfilPegawai::factory()->create();

        $response = $this->postJson('/pegawai/bonus-karyawan/create', [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => $profile->id,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'NEW',
            'total_diambil' => 'taken',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Bonus Karyawan berhasil dibuat',
            ]);

    }

    /** @test */
    public function it_returns_an_error_if_profile_not_found()
    {
        $response = $this->postJson('api/pegawai/bonus-karyawan/create', [
            'bulan' => 11,
            'tahun' => 2023,
            'id_profile_pegawai' => 999,
            'total_bonus' => 5000.00,
            'alasan_bonus' => 'Achievement bonus',
            'bukti_bonus' => 'proof123',
            'status' => 'NEW',
            'total_diambil' => 'taken',
        ]);

        $response->assertStatus(422);
    }
}

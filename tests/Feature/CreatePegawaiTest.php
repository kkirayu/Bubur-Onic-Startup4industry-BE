<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePegawaiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithFaker;

    public function testCreatePegawaiValidation()
    {

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $payload = [
            'name' => 'John Doe',
            'password' => 'password',
            'email' => $this->faker->unique()->safeEmail,
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
            'team_id' => 1,
        ];

        dump("/api/pegawai/profil-pegawai/store_with_user");
        dump(json_encode($payload,JSON_PRETTY_PRINT));
        $response = $this->postJson('/api/pegawai/profil-pegawai/store_with_user', $payload );

        // check profile_pegawais table
        $this->assertDatabaseHas('profile_pegawais', [
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
            'team_id' => 1,
        ]);
        // check users table
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => $payload['email'],
        ]);
        

        $response->assertStatus(201);

        

    }
}

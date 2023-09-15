<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profil_pegawais', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->integer('user_id');
            $table->string('kode_pegawai')->unique();
            $table->text('alamat');
            $table->text('alamat_asal');
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->date('tanggal_lahir');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar');
            $table->boolean('tanggal_kawin');
            $table->string('nomor_ktp')->nullable();
            $table->string('npwp')->nullable();

            $table->double('gaji_pokok');
            $table->double('uang_hadir');
            $table->double('tunjangan_jabatan');
            $table->double('tunjangan_tambahan');
            $table->double('extra_rajin');
            $table->double('thr');
            $table->double('tunjangan_lembur');

            $table->integer('quota_cuti_tahunan');
            $table->integer('team_id');

            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};

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
            $table->integer('perusahaan_id')->default(0);
            $table->integer('cabang_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('kode_pegawai')->unique();
            $table->text('alamat');
            $table->text('alamat_asal')->nullable();
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->date('tanggal_lahir');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->boolean('status_kawin');
            $table->string('nomor_ktp')->nullable();
            $table->string('npwp')->nullable();

            $table->double('gaji_pokok')->default(0.0);
            $table->double('uang_hadir')->default(0.0);
            $table->double('tunjangan_jabatan')->default(0.0);
            $table->double('tunjangan_tambahan')->default(0.0);
            $table->double('extra_rajin')->default(0.0);
            $table->double('thr')->default(0.0);
            $table->double('tunjangan_lembur')->default(0.0);

            $table->integer('quota_cuti_tahunan')->default(0);
            $table->integer('team_id')->default(0);

            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pegawais');
    }
};

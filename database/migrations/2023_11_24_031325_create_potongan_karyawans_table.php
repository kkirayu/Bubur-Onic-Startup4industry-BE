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
        Schema::create('potongan_karyawans', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->unsignedBigInteger('id_profile_pegawai');
            $table->double('total_potongan');
            $table->text('alasan_potongan');
            $table->string('bukti_potongan');
            $table->enum('status', ['BARU', 'KIRIM', 'DIAJUKAN', 'DIBATALKAN']);
            $table->string('total_diambil');
            $table->timestamps();
            $table->userstamps();
        });

        Schema::table('potongan_karyawans', function (Blueprint $table) {
            $table->foreign('id_profile_pegawai')->references('id')->on('profile_pegawais');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_karyawans');
    }
};

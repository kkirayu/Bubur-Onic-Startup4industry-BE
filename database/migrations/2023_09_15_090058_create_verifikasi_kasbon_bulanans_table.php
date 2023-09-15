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
        Schema::create('verifikasi_kasbon_bulanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kasbon_bulanan_id');
            $table->unsignedBigInteger('profile_pegawai_id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');
            $table->string('status');

            $table->timestamps();
            $table->userstamps();

            $table->foreign('profile_pegawai_id')->references('id')->on('profile_pegawais');
            $table->foreign('kasbon_bulanan_id')->references('id')->on('kasbon_bulanans');
            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
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

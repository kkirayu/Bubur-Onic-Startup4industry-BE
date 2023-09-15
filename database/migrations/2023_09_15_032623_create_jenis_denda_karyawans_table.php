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
        Schema::create('jenis_denda_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');
            $table->text('deskripsi');
            $table->unsignedBigInteger('akun_id');

            $table->timestamps();
            $table->userstamps();

            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('akun_id')->references('id')->on('akuns');
            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
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

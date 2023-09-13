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
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun');
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->string('nama');
            $table->text('deskripsi');
            $table->integer('kategori_akun_id');
            $table->string('posisi_akun');
            $table->integer('is_kas');
            $table->integer('parent_akun');
            $table->timestamps();
            $table->userstamps();

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('kategori_akun_id')->references('id')->on('kategori_akuns');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};

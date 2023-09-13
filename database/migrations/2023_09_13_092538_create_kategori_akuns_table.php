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
        Schema::create('kategori_akuns', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('parent_kategori_akun')->nullable();

            $table->timestamps();
            $table->userstamps();

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('parent_kategori_akun')->references('id')->on('kategori_akuns');
            
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

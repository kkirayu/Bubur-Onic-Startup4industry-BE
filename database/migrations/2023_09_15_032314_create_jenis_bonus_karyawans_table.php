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
        Schema::create('jenis_bonus_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->text('deskripsi');
            $table->integer('akun_id');

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

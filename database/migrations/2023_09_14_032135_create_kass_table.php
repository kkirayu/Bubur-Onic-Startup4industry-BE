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
        Schema::create('kass', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kas');
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->integer('akun_id');
            $table->text('deskripsi');
            $table->timestamp('posted_at');
            $table->integer('posted_by');

            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kass');
    }
};

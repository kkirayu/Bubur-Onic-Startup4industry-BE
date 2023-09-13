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
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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

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
        Schema::create('journal_akuns', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->integer('journal_id');
            $table->integer('akun');
            $table->string('posisi_akun');
            $table->text('deskripsi');
            $table->double('jumlah');
            $table->timestamps();
            $table->userstamps();
            $table->foreign('journal_id')->references('id')->on('journals');
            $table->foreign('akun')->references('id')->on('akuns');
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

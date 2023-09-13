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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurnal');
            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->double('total_debit');
            $table->double('total_kredit');
            $table->text('deskripsi');
            $table->timestamp('posted_at');
            $table->integer('posted_by');
            $table->timestamps();
            $table->userstamps();
            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};

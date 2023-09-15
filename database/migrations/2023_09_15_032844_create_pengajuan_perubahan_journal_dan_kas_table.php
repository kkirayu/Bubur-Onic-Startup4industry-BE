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
        Schema::create('pengajuan_perubahan_journal_dan_kas', function (Blueprint $table) {
            $table->id();
            $table->string("process_instance_id")->nullable();

            $table->integer('perusahaan_id');
            $table->integer('cabang_id');
            $table->text('payload');
            $table->string('nama');
            $table->string('jenis_aksi');


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
        Schema::dropIfExists('pengajuan_perubahan_journal_dan_kas');
    }
};

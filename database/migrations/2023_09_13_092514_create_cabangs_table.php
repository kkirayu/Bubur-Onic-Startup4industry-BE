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
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cabang');
            $table->unsignedBigInteger('perusahaan_id');
            $table->string('nama');
            $table->text('alamat');


            $table->timestamps();
            $table->userstamps();

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

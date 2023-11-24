<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Check if the columns exist before adding them
        if (!Schema::hasColumn('potongan_karyawans', 'perusahaan_id')) {
            Schema::table('potongan_karyawans', function (Blueprint $table) {
                $table->unsignedBigInteger('perusahaan_id');
                $table->foreign('perusahaan_id')->references('id')->on('perusahaans')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('potongan_karyawans', 'cabang_id')) {
            Schema::table('potongan_karyawans', function (Blueprint $table) {
                $table->unsignedBigInteger('cabang_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potongan_karyawans', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('assets', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("perusahaan_id")->nullable();
            $table->unsignedBigInteger("cabang_id")->nullable();

            $table->foreign("perusahaan_id")->references("id")->on("perusahaans");
            $table->foreign("cabang_id")->references("id")->on("cabangs");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};

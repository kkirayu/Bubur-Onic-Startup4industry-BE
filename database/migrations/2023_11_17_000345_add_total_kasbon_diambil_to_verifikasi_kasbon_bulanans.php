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
        Schema::table('verifikasi_kasbon_bulanans', function (Blueprint $table) {
            $table->double("total_kasbon");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_kasbon_bulanans', function (Blueprint $table) {
            $table->dropColumn("total_kasbon");
            //
        });
    }
};

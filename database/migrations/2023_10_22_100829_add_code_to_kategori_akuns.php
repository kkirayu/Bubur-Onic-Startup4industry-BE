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
        Schema::table('kategori_akuns', function (Blueprint $table) {
            //
            $table->string('code');
            $table->string('prefix_akun')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_akuns', function (Blueprint $table) {
            //
            $table->dropColumn('code');
            $table->dropColumn('prefix_akun')->nullable();
        });
    }
};

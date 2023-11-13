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
        Schema::table('akuns', function (Blueprint $table) {
            //
            $table->dropColumn("parent_akun");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            //
            $table->dropForeign('parent_akun')->references('id')->on('akuns');
            $table->integer('parent_akun');
        });
    }
};

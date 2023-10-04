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
            $table->dropColumn('posisi_akun');

            $table->integer('parent_akun')->nullable()->change();

            $table->foreign('parent_akun')->references('id')->on('akuns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            //
            $table->string('posisi_akun');
        });
    }
};

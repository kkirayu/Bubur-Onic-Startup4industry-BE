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
        Schema::table('bonus_karyawans', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('bonus_karyawans', function (Blueprint $table) {
            $table->enum('status', ['BARU', 'DIAJUKAN', 'DIBATALKAN'])->default('BARU');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus_karyawans', function (Blueprint $table) {
            // Completely remove the 'status' column during rollback
            $table->dropColumn('status');
        });
    }
};

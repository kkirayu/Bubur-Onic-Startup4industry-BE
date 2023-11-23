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
        Schema::table('invoices', function (Blueprint $table) {

            $table->enum('post_status', ['DRAFT', 'POSTED'])->default('DRAFT');
        });
        Schema::table('bills', function (Blueprint $table) {

            $table->enum('post_status', ['DRAFT', 'POSTED'])->default('DRAFT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropColumn('post_status');

        });

        Schema::table('bills', function (Blueprint $table) {

            $table->dropColumn('post_status');
        });
    }
};

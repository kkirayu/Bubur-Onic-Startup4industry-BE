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
                $table->enum('post_status', ['DRAFT', 'POSTED'])->default('DRAFT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
            $table->dropColumn('post_status', ['DRAFT', 'POSTED'])->default('DRAFT');
        });
    }
};

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
        Schema::table('asset_depreciation_history', function (Blueprint $table) {
            //
            $table->date("date")->nullable();
            $table->text("description")->nullable();
            $table->unsignedBigInteger("journal_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_depreciation_history', function (Blueprint $table) {
            //
            $table->dropColumn("date")->nullable();
            $table->dropColumn("description")->nullable();
        });
    }
};

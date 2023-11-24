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
        Schema::table('asset_category', function (Blueprint $table) {
            //

            $table->unsignedBigInteger("account_id")->nullable()->change();
            $table->unsignedBigInteger("depreciation_account_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_category', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("account_id")->nullable()->change();
            $table->unsignedBigInteger("depreciation_account_id")->nullable()->change();
        });
    }
};

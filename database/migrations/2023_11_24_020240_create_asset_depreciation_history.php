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
        Schema::create('asset_depreciation_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("asset_id");
            $table->double("depreciation_value");
            $table->unsignedBigInteger("journal_id");

            $table->foreign("journal_id")->references("id")->on("journals");
            $table->foreign("asset_id")->references("id")->on("assets");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_depreciation_history');
    }
};

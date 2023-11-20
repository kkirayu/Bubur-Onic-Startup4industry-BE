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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->date("purchase_date");
            $table->date("start_depreciation_date")->nullable();
            $table->double("gross_value");
            $table->double("salvage_value");
            $table->double("residual_value");
            $table->string("description");
            $table->unsignedBigInteger("supplier");
            $table->unsignedBigInteger("asset_category_id");
            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

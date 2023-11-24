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
        Schema::create('asset_category', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->unsignedBigInteger("account_id")->nullable();
            $table->unsignedBigInteger("depreciation_account_id")->nullable();

            $table->foreign("account_id")->references("id")->on("akuns");
            $table->foreign("depreciation_account_id")->references("id")->on("akuns");

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_category');
    }
};

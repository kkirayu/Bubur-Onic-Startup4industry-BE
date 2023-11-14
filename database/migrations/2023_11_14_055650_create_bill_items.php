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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bill_id")->nullable();
            $table->unsignedBigInteger("product_id")->nullable();
            $table->double("qty")->nullable();
            $table->double("price")->nullable();
            $table->double("total")->nullable();
            $table->double("discount")->nullable();
            $table->double("tax")->nullable();
            $table->double("subtotal")->nullable();
            $table->double("description")->nullable();
        
            $table->unsignedBigInteger("account_id")->nullable();
            $table->foreign('account_id')->references('id')->on('akuns');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign("bill_id")->references("id")->on("bills");
            $table->foreign("product_id")->references("id")->on("products");
            $table->userstamps();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};

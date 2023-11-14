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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            $table->unsignedBigInteger('kategori')->nullable();
            $table->unsignedBigInteger('brand')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('selling_price')->nullable();
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('unit')->references('id')->on('units');
            $table->foreign('kategori')->references('id')->on('product_kategoris');
            $table->foreign('brand')->references('id')->on('brands');

            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

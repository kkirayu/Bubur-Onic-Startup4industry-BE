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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("invoice_id")->nullable();
            $table->unsignedBigInteger("payment_method_id")->nullable();
            $table->double("amount")->nullable();
            $table->double("description")->nullable();
            $table->date("payment_date")->nullable();

            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign("invoice_id")->references("id")->on("invoices");
            $table->foreign("payment_method_id")->references("id")->on("payment_methods");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};

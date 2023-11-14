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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->string("bill_number")->nullable();
            $table->date("bill_date")->nullable();
            $table->date("due_date")->nullable();
            $table->unsignedBigInteger("supplier_id")->nullable();
            $table->double("total")->nullable();
            $table->double("paid_total")->nullable();
            $table->text("desc")->nullable();

            $table->string("payment_status")->nullable();


            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('cabang_id');


            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('supplier_id')->references("id")->on("suppliers");
            $table->timestamps();
            $table->userstamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};

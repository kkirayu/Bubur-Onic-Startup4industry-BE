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
        Schema::create('pengajuan_perubahan_journal_dan_kas_review_direksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("business_key")->nullable();
            $table->string("review_direksi");

            $table->timestamps();
            $table->userstamps();

            $table->foreign('business_key')->references('id')->on('pengajuan_perubahan_journal_dan_kas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perubahan_journal_dan_kas_review_direksi');
    }
};

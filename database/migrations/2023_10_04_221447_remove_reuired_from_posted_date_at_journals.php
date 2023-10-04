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
        Schema::table('journals', function (Blueprint $table) {
            //
            $table->date('posted_at')->nullable()->change();
            $table->integer('posted_by')->nullable()->change();

            $table->foreign('posted_by')->references('id')->on('users');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            //
            $table->date('posted_at')->nullable()->change();
            $table->integer('posted_by')->nullable()->change();
        });
    }
};

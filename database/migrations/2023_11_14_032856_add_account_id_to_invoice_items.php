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
    Schema::table('invoice_items', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("account_id")->nullable();
            $table->foreign("account_id")->references("id")->on("akuns");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign("account_id")->references("id")->on("accounts");
            $table->dropColumn("account_id")->nullable();
        });
    }
};

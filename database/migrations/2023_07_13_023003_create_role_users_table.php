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
        Schema::create('role_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('role_id');
            $table->timestamps();
            $table->softDeletes();
            $table->userstamps();

           $table->foreign('user_id')->references('id')->on('users');
           $table->foreign('role_id')->references('id')->on('acl_roles');
           $table->foreign('created_by')->references('id')->on('users');
           $table->foreign('updated_by')->references('id')->on('users');
           $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_users');
    }
};

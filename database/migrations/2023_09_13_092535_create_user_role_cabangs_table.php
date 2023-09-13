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
        Schema::create('user_role_cabangs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->uuid('acl_roles_id');



            $table->timestamps();
            $table->userstamps();

            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('perusahaan_id')->references('id')->on('perusahaans');
            $table->foreign('acl_roles_id')->references('id')->on('acl_roles');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};

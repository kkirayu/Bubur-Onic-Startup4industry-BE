<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bonus_karyawans', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('bulan');
            $table->tinyInteger('tahun');
            $table->unsignedBigInteger('id_profile_pegawai')->comment('Reference to id in profile_pegawais table');
            $table->double('total_bonus');
            $table->text('alasan_bonus');
            $table->string('bukti_bonus', 255);
            $table->enum('status', ['NEW', 'SUBMIT', 'APPROVED', 'REJECTED']);
            $table->string('total_diambil')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add foreign key constraint
        Schema::table('bonus_karyawans', function (Blueprint $table) {
            $table->foreign('id_profile_pegawai')->references('id')->on('profile_pegawais');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonus_karyawans');
    }
};

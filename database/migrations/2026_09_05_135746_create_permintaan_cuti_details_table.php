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
        Schema::create('permintaan_cuti_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('permintaan_cuti_id');
            $table->date('tanggal');
            $table->boolean('setengah_hari')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('permintaan_cuti_id')->references('id')->on('permintaan_cutis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_cuti_details');
    }
};

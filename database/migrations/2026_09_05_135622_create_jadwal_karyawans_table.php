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
        Schema::create('jadwal_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shift_id');
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tanggal');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('karyawan_id')->references('id')->on('karyawans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_karyawans');
    }
};

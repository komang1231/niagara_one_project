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
        Schema::create('permintaan_tukar_shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('karyawan_pengaju');
            $table->unsignedInteger('karyawan_pengganti');
            $table->date('tanggal_tujuan');
            $table->unsignedInteger('shift_pengaju');
            $table->unsignedInteger('shift_pengganti');
            $table->unsignedBigInteger('approved_by');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('karyawan_pengaju')->references('id')->on('karyawans');
            $table->foreign('karyawan_pengganti')->references('id')->on('karyawans');
            $table->foreign('shift_pengaju')->references('id')->on('shifts');
            $table->foreign('shift_pengganti')->references('id')->on('shifts');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_tukar_shifts');
    }
};

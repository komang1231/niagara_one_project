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
        Schema::create('history_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_sk', 50)->unique();
            $table->string('file_sk', 255);
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedInteger('level_lama');
            $table->unsignedInteger('level_baru');
            $table->unsignedInteger('posisi_lama');
            $table->unsignedInteger('posisi_baru');
            $table->unsignedInteger('cabang_lama')->nullable();
            $table->unsignedInteger('cabang_baru')->nullable();
            $table->enum('jenis_perubahan', ['promosi', 'demosi', 'rotasi', 'mutasi']);
            $table->date('tanggal_efektif');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('level_lama')->references('id')->on('job_levels');
            $table->foreign('level_baru')->references('id')->on('job_levels');
            $table->foreign('posisi_lama')->references('id')->on('job_positions');
            $table->foreign('posisi_baru')->references('id')->on('job_positions');
            $table->foreign('cabang_lama')->references('id')->on('cabang_kantor');
            $table->foreign('cabang_baru')->references('id')->on('cabang_kantor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_karyawans');
    }
};

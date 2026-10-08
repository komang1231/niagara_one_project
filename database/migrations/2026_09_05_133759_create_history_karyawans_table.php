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
            $table->string('kode', 50)->unique();
            $table->string('file_sk', 255);
            $table->unsignedBigInteger('karyawan_id');

            // Cabang (Lokasi Kerja)
            $table->unsignedInteger('cabang_lama')->nullable();
            $table->unsignedInteger('cabang_baru')->nullable();

            // Organisasi (Chained Relationship)
            $table->unsignedInteger('departemen_lama');
            $table->unsignedInteger('departemen_baru');
            $table->unsignedInteger('divisi_lama');
            $table->unsignedInteger('divisi_baru');
            $table->unsignedInteger('section_lama');
            $table->unsignedInteger('section_baru');

            // Jabatan & Tingkatan
            $table->unsignedInteger('posisi_lama');
            $table->unsignedInteger('posisi_baru');
            $table->unsignedInteger('level_lama');
            $table->unsignedInteger('level_baru');

            $table->enum('jenis_perubahan', ['promosi', 'demosi', 'rotasi', 'mutasi']);
            $table->date('tanggal_efektif');
            $table->timestamp('diterapkan_at')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('cabang_lama')->references('id')->on('cabang_kantors');
            $table->foreign('cabang_baru')->references('id')->on('cabang_kantors');
            $table->foreign('departemen_lama')->references('id')->on('departemens');
            $table->foreign('departemen_baru')->references('id')->on('departemens');
            $table->foreign('divisi_lama')->references('id')->on('divisis');
            $table->foreign('divisi_baru')->references('id')->on('divisis');
            $table->foreign('section_lama')->references('id')->on('sections');
            $table->foreign('section_baru')->references('id')->on('sections');
            $table->foreign('posisi_lama')->references('id')->on('job_positions');
            $table->foreign('posisi_baru')->references('id')->on('job_positions');
            $table->foreign('level_lama')->references('id')->on('job_levels');
            $table->foreign('level_baru')->references('id')->on('job_levels');
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

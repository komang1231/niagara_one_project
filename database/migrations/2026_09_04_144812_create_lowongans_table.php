<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->string('judul', 100);
            $table->unsignedInteger('permintaan_karyawan_id')->nullable();
            $table->unsignedInteger('cabang_kantor_id');
            $table->unsignedInteger('departemen_id');
            $table->unsignedInteger('divisi_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('job_position_id')->nullable();
            $table->unsignedInteger('job_level_id');
            $table->unsignedInteger('kuota');
            $table->text('kualifikasi');
            $table->text('deskripsi');
            $table->decimal('min_gaji', 20, 4);
            $table->decimal('max_gaji', 20, 4);
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('permintaan_karyawan_id')->references('id')->on('permintaan_karyawans');
            $table->foreign('cabang_kantor_id')->references('id')->on('cabang_kantor');
            $table->foreign('departemen_id')->references('id')->on('departemens');
            $table->foreign('divisi_id')->references('id')->on('divisis');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->foreign('job_level_id')->references('id')->on('job_levels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
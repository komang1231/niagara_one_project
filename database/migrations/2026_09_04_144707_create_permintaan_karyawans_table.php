<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_karyawans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->unsignedBigInteger('karyawan_id'); // pemohon — FK ditambahkan belakangan (circular)
            $table->unsignedInteger('job_position_id');
            $table->unsignedInteger('job_level_id');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('approved_by')->nullable(); // FK ke users — ditambahkan belakangan (circular)
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->foreign('job_level_id')->references('id')->on('job_levels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_karyawans');
    }
};
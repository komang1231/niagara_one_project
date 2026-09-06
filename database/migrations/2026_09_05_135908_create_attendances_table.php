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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedInteger('shift_id');
            $table->date('tanggal');
            $table->decimal('latitude', 12, 8);
            $table->decimal('longitude', 12, 8);
            $table->boolean('is_manual')->default(false);
            $table->text('keterangan')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->integer('total_menit_terlambat')->nullable();
            $table->integer('total_menit_pulang_cepat')->nullable();
            $table->integer('total_menit_kerja')->nullable();
            $table->unsignedBigInteger('input_by');
            $table->enum('status', ['hadir', 'terlambat', 'sakit', 'cuti', 'alpa', 'izin', 'libur', 'libur_nasional'])->default('hadir');
            // $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('input_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

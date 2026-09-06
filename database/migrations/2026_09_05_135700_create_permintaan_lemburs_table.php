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
        Schema::create('permintaan_lemburs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique();
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tanggal_tujuan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('alasan');
            $table->decimal('pengali', 2, 1);
            $table->unsignedBigInteger('approved_by');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_lemburs');
    }
};

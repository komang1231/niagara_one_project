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
        Schema::create('dokumen_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('karyawan_id');
            $table->enum('jenis_dokumen', ['KTP', 'KK', 'Ijazah', 'SKCK']);
            $table->string('file', 255);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('karyawan_id')->references('id')->on('karyawans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_karyawans');
    }
};

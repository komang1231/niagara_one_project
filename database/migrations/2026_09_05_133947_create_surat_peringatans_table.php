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
        Schema::create('surat_peringatans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique();
            $table->unsignedBigInteger('karyawan_id');
            $table->enum('jenis_surat', ['SP1', 'SP2', 'SP3']);
            $table->string('file', 255);
            $table->date('masa_berlaku');
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
        Schema::dropIfExists('surat_peringatans');
    }
};

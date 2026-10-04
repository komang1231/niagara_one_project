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
        Schema::create('kontrak_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_kontrak', 20)->unique();
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedInteger('status_kepegawaian_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            //TAMBAH//karyawan A,PKWTT,1 JAN 2025,1 JAN 2026(BISA)
            //TAMBAH//karyawan A,PKWTT,1 JAN 2026,1 JAN 2027(GABISA)
            //GABISA TAMBAH KONTRAK YG KARYAWANNYA SUDAH ADA DATA KONTRAK(GA MUNCUL DI FORM CREATE)
            //TOMBOL PERPANJANG BISA DI KLIK SAAT H-30
            //PERPANJANG//karyawan A,PKWTT,1 JAN 2026,1 JAN 2027(BISA)

            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('status_kepegawaian_id')->references('id')->on('status_kepegawaians');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_karyawans');
    }
};

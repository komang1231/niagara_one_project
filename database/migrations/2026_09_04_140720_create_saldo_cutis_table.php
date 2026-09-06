<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_cutis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cuti_id');
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('saldo');
            $table->unsignedInteger('terpakai')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cuti_id')->references('id')->on('cutis');
            // $table->foreign('karyawan_id')->references('id')->on('karyawans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_cutis');
    }
};
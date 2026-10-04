<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_cutis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('saldo');
            $table->unsignedInteger('terpakai')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['karyawan_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_cutis');
    }
};

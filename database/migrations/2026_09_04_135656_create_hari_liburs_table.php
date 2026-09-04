<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hari_liburs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->date('tanggal');
            $table->string('nama', 150);
            // $table->unsignedInteger('cabang_kantor_id')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('cabang_kantor_id')->references('id')->on('cabang_kantor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_liburs');
    }
};
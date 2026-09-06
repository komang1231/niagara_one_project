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
        Schema::create('closing_periode_absensis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique(); // bulan + tahun, biar gaada duplicate
            $table->integer('bulan');
            $table->integer('tahun');
            $table->unsignedBigInteger('closed_by');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bulan', 'tahun']); // memastikan tidak ada duplicate bulan dan tahun
            $table->foreign('closed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_periode_absensis');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('divisi_id');
            $table->string('nama', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('divisi_id')->references('id')->on('divisis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
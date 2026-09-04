<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('section_id');
            $table->string('nama', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('section_id')->references('id')->on('sections');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_positions');
    }
};
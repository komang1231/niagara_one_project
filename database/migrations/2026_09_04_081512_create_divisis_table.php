<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('departemen_id')->nullable();
            $table->string('nama', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('departemen_id')->references('id')->on('departemens');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisis');
    }
};
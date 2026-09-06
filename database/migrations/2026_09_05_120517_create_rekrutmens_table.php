<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekrutmens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('lowongan_id');
            // $table->unsignedInteger('lowongan_id')->nullable();
            $table->unsignedInteger('job_position_id');
            $table->unsignedInteger('job_level_id');    
            $table->unsignedInteger('cabang_kantor_id');
            $table->string('nama', 100);
            $table->string('email', 150)->unique();
            $table->string('no_hp', 20)->unique();
            $table->string('file_cv', 255);
            $table->unsignedInteger('jenjang_pendidikan_id');
            $table->unsignedInteger('sumber_pelamar_id');
            $table->enum('pool_talent', ['rehire', 'blacklist'])->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lowongan_id')->references('id')->on('lowongans');
            $table->foreign('cabang_kantor_id')->references('id')->on('cabang_kantor');
            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->foreign('job_level_id')->references('id')->on('job_levels');
            $table->foreign('jenjang_pendidikan_id')->references('id')->on('jenjang_pendidikans');
            $table->foreign('sumber_pelamar_id')->references('id')->on('sumber_pelamars');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekrutmens');
    }
};
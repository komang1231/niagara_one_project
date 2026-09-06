<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Psy\CodeCleaner\NamespaceAwarePass;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip', 20)->unique();
            $table->unsignedBigInteger('rekrutmen_id');
            // $table->unsignedInteger('rekrutmen_id')->nullable();
            // $table->unsignedInteger('lowongan_id');
            $table->unsignedInteger('job_position_id');
            $table->unsignedInteger('job_level_id');    
            $table->unsignedInteger('cabang_kantor_id');
            $table->decimal('gaji', 20, 4);
            $table->string('nama', 100);
            $table->string('email', 150)->unique();
            $table->string('no_hp', 20)->unique();
            $table->char('nik', 16)->unique();
            $table->char('no_bpjs_ketenagakerjaan', 11)->unique();
            $table->char('no_bpjs_kesehatan', 13)->unique();
            $table->char('no_npwp', 16)->unique();
            $table->unsignedInteger('jenjang_pendidikan_id');
            $table->unsignedInteger('status_kawin_id');
            $table->unsignedInteger('agama_id');
            $table->unsignedInteger('status_kepegawaian_id');
            $table->unsignedInteger('bank_id');
            $table->string('nama_bank', 100);
            $table->string('no_rekening', 30)->unique();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rekrutmen_id')->references('id')->on('rekrutmens');
            // $table->foreign('lowongan_id')->references('id')->on('lowongans');
            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->foreign('job_level_id')->references('id')->on('job_levels');
            $table->foreign('cabang_kantor_id')->references('id')->on('cabang_kantor');
            $table->foreign('jenjang_pendidikan_id')->references('id')->on('jenjang_pendidikans');
            $table->foreign('status_kawin_id')->references('id')->on('status_kawins');
            $table->foreign('agama_id')->references('id')->on('agamas');
            $table->foreign('status_kepegawaian_id')->references('id')->on('status_kepegawaians');
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
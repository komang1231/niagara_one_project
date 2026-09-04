<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabang_kantor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->decimal('latitude', 12, 8);
            $table->decimal('longitude', 12, 8);
            $table->unsignedInteger('radius_geofence');
            $table->text('alamat_lengkap');
            $table->unsignedInteger('kecamatan_id');
            $table->string('kode_pos', 10);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kecamatan_id')->references('id')->on('kecamatans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang_kantor');
    }
};
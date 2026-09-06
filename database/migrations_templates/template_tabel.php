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
        $table->bigIncrements('id');
        $table->string('kode', 20)->unique();
        $table->string('nama', 255);
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        $table->timestamps();
        $table->softDeletes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saldo_cutis', function (Blueprint $table) {
            $table->foreign('karyawan_id')->references('id')->on('karyawans');
        });

        Schema::table('permintaan_karyawans', function (Blueprint $table) {
            $table->foreign('karyawan_id')->references('id')->on('karyawans');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::table('saldo_cutis', function (Blueprint $table) {
            $table->dropForeign(['karyawan_id']);
        });

        Schema::table('permintaan_karyawans', function (Blueprint $table) {
            $table->dropForeign(['karyawan_id']);
            $table->dropForeign(['approved_by']);
        });
    }
};
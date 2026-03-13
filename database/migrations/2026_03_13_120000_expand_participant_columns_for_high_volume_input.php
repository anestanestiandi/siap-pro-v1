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
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            $table->text('rombongan')->nullable()->change();
        });

        Schema::table('administrasi_perjalanan_dinas', function (Blueprint $table) {
            $table->text('pelaksana')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            $table->string('rombongan', 500)->nullable()->change();
        });

        Schema::table('administrasi_perjalanan_dinas', function (Blueprint $table) {
            $table->string('pelaksana', 255)->change();
        });
    }
};

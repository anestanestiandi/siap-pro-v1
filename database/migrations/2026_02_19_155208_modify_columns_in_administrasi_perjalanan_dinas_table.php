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
        Schema::table('administrasi_perjalanan_dinas', function (Blueprint $table) {
            // Adjust types to match master tables (int)
            $table->integer('id_petugas')->nullable()->change();
            $table->integer('id_anggota')->nullable()->change();
            
            // Add foreign keys
            $table->foreign('id_petugas')->references('id_petugas')->on('master_petugas_protokol');
            $table->foreign('id_anggota')->references('id_anggota')->on('master_anggota_dewan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrasi_perjalanan_dinas', function (Blueprint $table) {
            $table->dropForeign(['id_petugas']);
            $table->dropForeign(['id_anggota']);
            // Reverting types to original if needed, but keeping them as they were mostly
            $table->unsignedInteger('id_petugas')->nullable()->change();
            $table->bigInteger('id_anggota')->unsigned()->nullable()->change();
        });
    }
};

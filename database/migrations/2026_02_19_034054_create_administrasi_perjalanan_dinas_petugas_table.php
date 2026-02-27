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
        if (!Schema::hasTable('administrasi_perjalanan_dinas_petugas')) {
            Schema::create('administrasi_perjalanan_dinas_petugas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_adm_perjalanan_dinas')->constrained('administrasi_perjalanan_dinas', 'id_adm_perjalanan_dinas')->onDelete('cascade');
                $table->unsignedInteger('id_petugas');
                $table->foreign('id_petugas')->references('id_petugas')->on('master_petugas_protokol')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrasi_perjalanan_dinas_petugas');
    }
};

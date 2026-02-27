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
        if (!Schema::hasTable('pelayanan_anggota_dewan')) {
            Schema::create('pelayanan_anggota_dewan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_pelayanan');
                $table->unsignedBigInteger('id_anggota');
                $table->timestamps();

                $table->foreign('id_pelayanan')->references('id_pelayanan')->on('pelayanan_keprotokolan')->onDelete('cascade');
                $table->foreign('id_anggota')->references('id_anggota')->on('master_anggota_dewan')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_anggota_dewan');
    }
};

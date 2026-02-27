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
        Schema::table('persidangan', function (Blueprint $table) {
            $table->integer('id_petugas')->nullable()->after('id_anggota');
            $table->foreign('id_petugas')->references('id_petugas')->on('master_petugas_protokol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persidangan', function (Blueprint $table) {
            $table->dropForeign(['id_petugas']);
            $table->dropColumn('id_petugas');
        });
    }
};

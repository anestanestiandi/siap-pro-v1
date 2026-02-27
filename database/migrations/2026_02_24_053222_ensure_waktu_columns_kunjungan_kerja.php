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
            // Rename 'waktu' to 'waktu_mulai' if it exists and 'waktu_mulai' doesn't
            if (Schema::hasColumn('kunjungan_kerja', 'waktu') && !Schema::hasColumn('kunjungan_kerja', 'waktu_mulai')) {
                $table->renameColumn('waktu', 'waktu_mulai');
            }

            // Ensure 'waktu_selesai' exists
            if (!Schema::hasColumn('kunjungan_kerja', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('tanggal_kunjungan');
            }
        });

        // Set waktu_mulai to nullable if it's currently not, to avoid "no default value" errors if needed
        // but since we usually fill it, we just want it to be named correctly.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan_kerja', 'waktu_mulai') && !Schema::hasColumn('kunjungan_kerja', 'waktu')) {
                $table->renameColumn('waktu_mulai', 'waktu');
            }
            if (Schema::hasColumn('kunjungan_kerja', 'waktu_selesai')) {
                $table->dropColumn('waktu_selesai');
            }
        });
    }
};

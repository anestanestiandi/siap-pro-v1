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
            // Check if 'waktu' exists before renaming
            if (Schema::hasColumn('kunjungan_kerja', 'waktu')) {
                $table->renameColumn('waktu', 'waktu_mulai');
            }
        });

        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            // Use 'after' on a column that definitely exists and isn't being renamed in this block
            if (!Schema::hasColumn('kunjungan_kerja', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('tanggal_kunjungan');
            }
            if (!Schema::hasColumn('kunjungan_kerja', 'file_path')) {
                $table->string('file_path', 500)->nullable()->after('rombongan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan_kerja', 'waktu_mulai')) {
                $table->renameColumn('waktu_mulai', 'waktu');
            }
            $table->dropColumn(['waktu_selesai', 'file_path']);
        });
    }
};

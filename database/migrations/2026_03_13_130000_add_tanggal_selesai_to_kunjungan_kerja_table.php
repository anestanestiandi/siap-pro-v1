<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan_kerja', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal_kunjungan');
                $table->index('tanggal_selesai', 'idx_tanggal_selesai');
            }
        });

        DB::table('kunjungan_kerja')
            ->whereNull('tanggal_selesai')
            ->update(['tanggal_selesai' => DB::raw('tanggal_kunjungan')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan_kerja', 'tanggal_selesai')) {
                if (Schema::hasIndex('kunjungan_kerja', 'idx_tanggal_selesai')) {
                    $table->dropIndex('idx_tanggal_selesai');
                }
                $table->dropColumn('tanggal_selesai');
            }
        });
    }
};

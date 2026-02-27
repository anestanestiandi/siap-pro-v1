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
            if (!Schema::hasColumn('kunjungan_kerja', 'tujuan_luar_negeri')) {
                $table->string('tujuan_luar_negeri', 255)->nullable()->after('id_provinsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            $table->dropColumn('tujuan_luar_negeri');
        });
    }
};

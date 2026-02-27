<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            // waktu: NOT NULL → nullable (field opsional di form)
            if (Schema::hasColumn('kunjungan_kerja', 'waktu')) {
                $table->time('waktu')->nullable()->change();
            }

            // rombongan: NOT NULL varchar → nullable with default '[]'
            if (Schema::hasColumn('kunjungan_kerja', 'rombongan')) {
                $table->string('rombongan', 500)->nullable()->default('[]')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan_kerja', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan_kerja', 'waktu')) {
                $table->time('waktu')->nullable(false)->change();
            }
            if (Schema::hasColumn('kunjungan_kerja', 'rombongan')) {
                $table->string('rombongan', 255)->nullable(false)->default(null)->change();
            }
        });
    }
};

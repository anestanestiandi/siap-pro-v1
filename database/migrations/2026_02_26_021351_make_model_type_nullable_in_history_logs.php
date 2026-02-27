<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make model_type and model_id nullable so login/logout logs
     * (which have no associated model) can be stored.
     */
    public function up(): void
    {
        Schema::table('history_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable()->change();
            $table->unsignedBigInteger('model_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable(false)->change();
            $table->unsignedBigInteger('model_id')->nullable(false)->change();
        });
    }
};

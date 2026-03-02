<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'inicio_semana_a')) {
                $table->date('inicio_semana_a')->nullable()->after('turno_actual_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'inicio_semana_a')) {
                $table->dropColumn('inicio_semana_a');
            }
        });
    }
};

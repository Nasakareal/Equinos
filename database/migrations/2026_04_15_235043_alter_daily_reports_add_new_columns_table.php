<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDailyReportsAddNewColumnsTable extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_reports', 'tipo')) {
                $table->string('tipo')->nullable()->after('id');
            }

            if (!Schema::hasColumn('daily_reports', 'fecha')) {
                $table->date('fecha')->nullable()->after('tipo');
            }

            if (!Schema::hasColumn('daily_reports', 'turno_id')) {
                $table->unsignedBigInteger('turno_id')->nullable()->after('fecha');
            }

            if (!Schema::hasColumn('daily_reports', 'archivo')) {
                $table->string('archivo')->nullable()->after('turno_id');
            }
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            if (Schema::hasColumn('daily_reports', 'archivo')) {
                $table->dropColumn('archivo');
            }

            if (Schema::hasColumn('daily_reports', 'turno_id')) {
                $table->dropColumn('turno_id');
            }

            if (Schema::hasColumn('daily_reports', 'fecha')) {
                $table->dropColumn('fecha');
            }

            if (Schema::hasColumn('daily_reports', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
}

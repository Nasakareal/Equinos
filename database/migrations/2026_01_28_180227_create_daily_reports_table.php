<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('tipo_reporte', 60);
            $table->unsignedBigInteger('turno_id')->nullable();

            $table->unsignedBigInteger('generado_por')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->foreign('turno_id')->references('id')->on('turnos')->nullOnDelete();
            $table->foreign('generado_por')->references('id')->on('users')->nullOnDelete();

            $table->unique(['fecha', 'tipo_reporte', 'turno_id'], 'daily_reports_unique');
            $table->index(['fecha', 'tipo_reporte']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
}

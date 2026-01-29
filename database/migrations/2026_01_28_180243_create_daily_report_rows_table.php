<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportRowsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_report_rows', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('daily_report_id');
            $table->unsignedBigInteger('personal_id')->nullable();

            $table->string('grado', 60)->nullable();
            $table->string('cuip', 30)->nullable();
            $table->string('nombre', 160)->nullable();
            $table->string('dependencia', 120)->nullable();

            $table->string('arma_corta', 120)->nullable();
            $table->string('matricula_corta', 60)->nullable();
            $table->string('arma_larga', 120)->nullable();
            $table->string('matricula_larga', 60)->nullable();

            $table->string('incidencia', 60)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('cargo', 160)->nullable();
            $table->string('crp', 40)->nullable();
            $table->string('area_sector', 160)->nullable();

            $table->time('hora_entrada')->nullable();
            $table->text('firma_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->text('firma_salida')->nullable();

            $table->text('despliegue_servicio')->nullable();
            $table->text('observaciones')->nullable();

            $table->unsignedInteger('orden')->default(0);

            $table->timestamps();

            $table->foreign('daily_report_id')->references('id')->on('daily_reports')->onDelete('cascade');
            $table->foreign('personal_id')->references('id')->on('personals')->nullOnDelete();

            $table->index(['daily_report_id', 'orden']);
            $table->index(['personal_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_report_rows');
    }
}

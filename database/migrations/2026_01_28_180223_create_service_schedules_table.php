<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');
            $table->unsignedBigInteger('turno_id')->nullable();

            $table->string('tipo', 20)->default('CICLICO');
            $table->date('fecha_inicio_ciclo');

            $table->unsignedInteger('horas_trabajo')->default(24);
            $table->unsignedInteger('horas_descanso')->default(24);

            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
            $table->foreign('turno_id')->references('id')->on('turnos')->nullOnDelete();

            $table->index(['personal_id', 'activo']);
            $table->index(['turno_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_schedules');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnoHorariosTable extends Migration
{
    public function up()
    {
        Schema::create('turno_horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('turno_id');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->unsignedInteger('min_tolerancia')->default(0);
            $table->boolean('cruza_dia')->default(false);
            $table->string('notas', 255)->nullable();

            $table->timestamps();

            $table->foreign('turno_id')->references('id')->on('turnos')->onDelete('cascade');
            $table->index(['turno_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('turno_horarios');
    }
}

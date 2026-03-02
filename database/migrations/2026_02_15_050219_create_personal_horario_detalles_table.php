<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_horario_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_horario_id');
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_entrada');
            $table->time('hora_salida');
            $table->unsignedSmallInteger('min_tolerancia')->default(0);
            $table->boolean('cruza_dia')->default(false);
            $table->unsignedTinyInteger('bloque')->default(1);
            $table->string('notas', 255)->nullable();
            $table->timestamps();

            $table->foreign('personal_horario_id')->references('id')->on('personal_horarios')->onDelete('cascade');

            $table->index(['personal_horario_id', 'dia_semana']);
            $table->unique(['personal_horario_id', 'dia_semana', 'bloque'], 'uniq_phd_horario_dia_bloque');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_horario_detalles');
    }
};

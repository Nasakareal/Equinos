<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoterapiaRegistrosTable extends Migration
{
    public function up()
    {
        Schema::create('equinoterapia_registros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equinoterapia_reporte_id')
                ->constrained('equinoterapia_reportes')
                ->onDelete('cascade');

            $table->string('nombre_completo');
            $table->enum('sexo', ['NIÑO', 'NIÑA']);
            $table->string('diagnostico')->nullable();

            $table->enum('estatus_asistencia', ['ASISTIO', 'INASISTIO'])->default('ASISTIO');
            $table->text('motivo_inasistencia')->nullable();

            $table->boolean('es_valoracion')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinoterapia_registros');
    }
}

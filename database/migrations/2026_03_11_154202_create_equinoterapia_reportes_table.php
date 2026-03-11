<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoterapiaReportesTable extends Migration
{
    public function up()
    {
        Schema::create('equinoterapia_reportes', function (Blueprint $table) {
            $table->id();

            $table->date('fecha')->unique();

            $table->unsignedInteger('valoraciones')->default(0);
            $table->unsignedInteger('personal')->default(0);
            $table->unsignedInteger('equinos')->default(0);

            $table->text('actividades_area')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinoterapia_reportes');
    }
}

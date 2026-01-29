<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidencesTable extends Migration
{
    public function up()
    {
        Schema::create('incidences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');
            $table->unsignedBigInteger('incidence_type_id');

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('comentario')->nullable();

            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
            $table->foreign('incidence_type_id')->references('id')->on('incidence_types')->onDelete('restrict');
            $table->foreign('registrado_por')->references('id')->on('users')->nullOnDelete();

            $table->index(['personal_id', 'fecha_inicio']);
            $table->index(['incidence_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidences');
    }
}

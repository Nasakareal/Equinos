<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidenceTypesTable extends Migration
{
    public function up()
    {
        Schema::create('incidence_types', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 60)->unique();
            $table->string('nombre', 120);
            $table->boolean('afecta_servicio')->default(true);
            $table->string('color', 30)->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidence_types');
    }
}

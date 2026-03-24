<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicio_participantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servicio_id');

            $table->string('institucion');
            $table->string('responsable')->nullable();
            $table->unsignedInteger('elementos')->nullable();
            $table->unsignedInteger('vehiculos')->nullable();
            $table->string('unidad_identificador')->nullable();
            $table->text('descripcion')->nullable();

            $table->timestamps();

            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_participantes');
    }
};

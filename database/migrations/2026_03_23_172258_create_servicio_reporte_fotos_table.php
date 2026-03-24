<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicio_reporte_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_reporte_id');

            $table->string('ruta');
            $table->string('nombre_original')->nullable();
            $table->string('mime', 150)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('descripcion')->nullable();

            $table->timestamps();

            $table->foreign('servicio_reporte_id')->references('id')->on('servicio_reportes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_reporte_fotos');
    }
};

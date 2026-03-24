<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicio_reportes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->string('tipo_reporte', 50);
            $table->date('fecha');
            $table->time('hora')->nullable();

            $table->string('municipio')->nullable();
            $table->string('lugar')->nullable();
            $table->string('asunto')->nullable();

            $table->text('narrativa')->nullable();
            $table->text('estado_fuerza_texto')->nullable();
            $table->text('acciones_a_realizar')->nullable();
            $table->text('acciones_realizadas')->nullable();
            $table->text('resultados')->nullable();
            $table->text('datos_persona_asegurada')->nullable();
            $table->text('conclusion')->nullable();

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->longText('whatsapp_texto')->nullable();

            $table->timestamps();

            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_reportes');
    }
};

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
        Schema::create('servicio_movimientos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->string('tipo_movimiento');
            $table->date('fecha');
            $table->time('hora')->nullable();

            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('acciones_realizadas')->nullable();
            $table->text('resultados')->nullable();
            $table->text('observaciones')->nullable();

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

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
        Schema::dropIfExists('servicio_movimientos');
    }
};

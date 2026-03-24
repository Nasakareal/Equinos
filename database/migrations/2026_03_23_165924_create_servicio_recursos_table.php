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
        Schema::create('servicio_recursos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servicio_id');

            $table->string('tipo_recurso'); // ELEMENTO, UNIDAD, CANINO, EQUINO, REMOLQUE, VETERINARIO, OTRO
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('cantidad')->default(1);

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
        Schema::dropIfExists('servicio_recursos');
    }
};

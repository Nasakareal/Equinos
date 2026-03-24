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
        Schema::create('servicio_estado_fuerza', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servicio_id');

            $table->unsignedInteger('elementos')->nullable();
            $table->unsignedInteger('unidades')->nullable();
            $table->unsignedInteger('remolques')->nullable();
            $table->unsignedInteger('equinos')->nullable();
            $table->unsignedInteger('caninos')->nullable();
            $table->unsignedInteger('medicos_veterinarios')->nullable();

            $table->string('crp')->nullable();
            $table->text('observaciones')->nullable();

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
        Schema::dropIfExists('servicio_estado_fuerza');
    }
};

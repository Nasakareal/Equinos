<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deployment_summaries', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->unsignedBigInteger('turno_id')->nullable();
            $table->string('area');
            $table->unsignedInteger('total_personal')->default(0);
            $table->unsignedInteger('total_unidades')->default(0);
            $table->unsignedInteger('unidades_en_servicio')->default(0);
            $table->unsignedInteger('unidades_en_base')->default(0);
            $table->unsignedInteger('unidades_en_taller')->default(0);
            $table->unsignedInteger('armas_cortas')->default(0);
            $table->unsignedInteger('armas_largas')->default(0);
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('turno_id')->references('id')->on('turnos')->nullOnDelete();
            $table->unique(['fecha', 'turno_id', 'area'], 'uniq_fecha_turno_area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployment_summaries');
    }
};

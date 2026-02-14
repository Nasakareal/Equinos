<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patrol_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('patrol_id');
            $table->date('fecha');
            $table->unsignedBigInteger('turno_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('servicio')->nullable();
            $table->string('zona')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->foreign('patrol_id')->references('id')->on('patrols')->onDelete('cascade');
            $table->foreign('turno_id')->references('id')->on('turnos')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unique(['patrol_id', 'fecha', 'turno_id'], 'uniq_patrol_fecha_turno');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_assignments');
    }
};

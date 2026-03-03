<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_medical_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();

            $table->date('fecha');

            $table->string('tipo');

            $table->text('descripcion')->nullable();

            $table->string('veterinario')->nullable();
            $table->decimal('costo', 10, 2)->nullable();

            $table->date('proxima_cita')->nullable();

            $table->timestamps();

            $table->index(['animal_id', 'fecha']);
            $table->index(['tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_medical_records');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_incidences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();

            $table->dateTime('fecha');

            $table->foreignId('incidence_type_id')->nullable()->constrained('incidence_types')->nullOnDelete();

            $table->enum('gravedad', ['BAJA', 'MEDIA', 'ALTA'])->default('BAJA');

            $table->text('descripcion')->nullable();

            $table->foreignId('atendido_por')->nullable()->constrained('users')->nullOnDelete();

            $table->boolean('resuelto')->default(false);
            $table->dateTime('resuelto_en')->nullable();

            $table->timestamps();

            $table->index(['animal_id', 'fecha']);
            $table->index(['incidence_type_id', 'fecha']);
            $table->index(['resuelto']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_incidences');
    }
};

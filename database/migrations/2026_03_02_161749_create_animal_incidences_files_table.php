<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_incidences_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_incidence_id')
                ->constrained('animal_incidences')
                ->cascadeOnDelete();

            $table->string('archivo');
            $table->string('tipo')->nullable();

            $table->timestamps();

            $table->index(['animal_incidence_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_incidences_files');
    }
};

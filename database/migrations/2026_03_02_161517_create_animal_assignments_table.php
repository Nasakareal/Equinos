<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();

            $table->foreignId('personal_id')->nullable()->constrained('personals')->nullOnDelete();

            $table->foreignId('turno_id')->nullable()->constrained('turnos')->nullOnDelete();
            $table->foreignId('patrol_id')->nullable()->constrained('patrols')->nullOnDelete();

            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(['animal_id', 'inicio']);
            $table->index(['personal_id', 'inicio']);
            $table->index(['patrol_id', 'inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_assignments');
    }
};

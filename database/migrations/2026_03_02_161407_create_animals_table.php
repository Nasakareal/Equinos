<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['EQUINO', 'CANINO']);
            $table->string('nombre');

            $table->string('raza')->nullable();
            $table->string('procedencia')->nullable();

            $table->enum('sexo', ['MACHO', 'HEMBRA'])->nullable();
            $table->string('color')->nullable();

            $table->text('caracteristicas')->nullable();

            $table->string('marcaje')->nullable();

            $table->string('chip')->nullable();

            $table->string('especialidad')->nullable();

            $table->enum('estatus', ['ACTIVO', 'BAJA', 'RESGUARDO'])->default('ACTIVO');

            $table->text('observaciones')->nullable();

            $table->date('fecha_nacimiento')->nullable();
            $table->string('edad_texto')->nullable();

            $table->decimal('forraje_kg_diario', 8, 2)->nullable();
            $table->decimal('grano_kg_diario', 8, 2)->nullable();

            $table->timestamps();

            $table->index(['tipo', 'estatus']);
            $table->index(['nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};

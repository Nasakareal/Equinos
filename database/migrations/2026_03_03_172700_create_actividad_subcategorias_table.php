<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_subcategorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_categoria_id')->constrained('actividad_categorias')->cascadeOnDelete();
            $table->string('nombre', 180);
            $table->string('slug', 220);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['actividad_categoria_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_subcategorias');
    }
};

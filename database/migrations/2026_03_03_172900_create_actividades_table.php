<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_categoria_id')->constrained('actividad_categorias')->restrictOnDelete();
            $table->foreignId('actividad_subcategoria_id')->nullable()->constrained('actividad_subcategorias')->nullOnDelete();

            $table->string('nombre', 255);
            $table->unsignedInteger('cantidad')->default(1);

            $table->string('foto_path', 255);
            $table->string('foto_nombre_original', 255)->nullable();
            $table->string('foto_hash', 128)->unique();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index(['actividad_categoria_id', 'actividad_subcategoria_id'], 'act_cat_sub_idx');
            $table->index(['created_at'], 'act_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};

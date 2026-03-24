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
        Schema::create('servicio_coordenadas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servicio_id');

            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(1);

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
        Schema::dropIfExists('servicio_coordenadas');
    }
};

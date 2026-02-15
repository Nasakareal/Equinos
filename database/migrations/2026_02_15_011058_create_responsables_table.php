<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');
            $table->unsignedBigInteger('area_id')->nullable();

            $table->enum('nivel', ['GENERAL', 'AREA']);
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();

            $table->index(['nivel', 'activo']);
            $table->index(['area_id', 'nivel', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responsables');
    }
};

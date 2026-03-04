<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puestas_disposicions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personal_id')
                ->constrained('personals')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedBigInteger('hecho_id')->nullable();

            $table->unsignedInteger('folio_num');
            $table->unsignedSmallInteger('anio');

            $table->string('folio', 20);

            $table->string('archivo_pdf', 255);

            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->unique(['anio', 'folio_num'], 'pd_anio_folio_unique');

            $table->index(['personal_id', 'anio']);
            $table->index('hecho_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puestas_disposicions');
    }
};

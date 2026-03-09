<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('personal_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');

            $table->string('tipo_documento')->nullable();
            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->string('archivo');
            $table->string('nombre_original')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('tamano')->nullable();

            $table->date('fecha_documento')->nullable();
            $table->text('observaciones')->nullable();

            $table->boolean('activo')->default(1);

            $table->timestamps();

            $table->foreign('personal_id')
                ->references('id')
                ->on('personals')
                ->onDelete('cascade');

            $table->index('personal_id');
            $table->index('tipo_documento');
            $table->index('fecha_documento');
            $table->index('activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_documents');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->unsignedBigInteger('canino_id')->nullable();
            $table->unsignedBigInteger('equino_id')->nullable();
            $table->unsignedBigInteger('patrulla_id')->nullable();

            $table->enum('categoria_registro', ['SERVICIO', 'APOYO', 'MEMORANDUM'])->default('SERVICIO');

            $table->enum('tipo_servicio', [
                'SEGURIDAD',
                'BARRIDO_SEGURIDAD',
                'BUSQUEDA',
                'DESFILE',
                'PROXIMIDAD_SOCIAL',
                'ACTO_CIVICO',
                'OTRO'
            ])->nullable();

            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();

            $table->boolean('cumplio')->default(false);

            $table->boolean('seguridad')->default(false);
            $table->boolean('barrido_seguridad')->default(false);
            $table->boolean('desfiles')->default(false);
            $table->boolean('proximidad_social')->default(false);
            $table->boolean('actos_civicos')->default(false);

            $table->enum('tipo_busqueda', [
                'EN VIDA',
                'RECURSO HUMANO',
                'EXPLOSIVO',
                'FORENSE',
                'NARCOTICOS'
            ])->nullable();

            $table->string('asunto')->nullable();
            $table->string('lugar')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();

            $table->string('archivo')->nullable();
            $table->string('archivo_nombre_original')->nullable();
            $table->string('archivo_mime')->nullable();
            $table->unsignedBigInteger('archivo_size')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('personal_id')->references('id')->on('personals')->nullOnDelete();
            $table->foreign('canino_id')->references('id')->on('animals')->nullOnDelete();
            $table->foreign('equino_id')->references('id')->on('animals')->nullOnDelete();
            $table->foreign('patrulla_id')->references('id')->on('patrols')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}

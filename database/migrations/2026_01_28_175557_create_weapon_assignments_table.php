<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeaponAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('weapon_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');
            $table->unsignedBigInteger('weapon_id');

            $table->dateTime('fecha_asignacion');
            $table->dateTime('fecha_devolucion')->nullable();

            $table->string('status', 30)->default('ASIGNADA');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
            $table->foreign('weapon_id')->references('id')->on('weapons')->onDelete('cascade');

            $table->index(['personal_id']);
            $table->index(['weapon_id']);
            $table->index(['weapon_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('weapon_assignments');
    }
}

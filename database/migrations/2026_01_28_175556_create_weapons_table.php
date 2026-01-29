<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeaponsTable extends Migration
{
    public function up()
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 20)->index();
            $table->string('marca_modelo', 120)->nullable();
            $table->string('matricula', 60)->unique();
            $table->string('estado', 30)->default('ACTIVA');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weapons');
    }
}

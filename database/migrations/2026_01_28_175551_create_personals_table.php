<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalsTable extends Migration
{
    public function up()
    {
        Schema::create('personals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('no_empleado', 40)->nullable();

            $table->string('cuip', 30)->nullable()->index();
            $table->string('grado', 60);
            $table->string('nombres', 160);
            $table->string('dependencia', 120)->nullable();
            $table->string('crp', 40)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('cargo', 160)->nullable();
            $table->boolean('es_responsable')->default(false);

            $table->string('area_patrullaje', 160)->nullable();
            $table->text('observaciones')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('personals');
    }
}

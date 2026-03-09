<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddEntidadToIncidenceTypesTable extends Migration
{
    public function up()
    {
        Schema::table('incidence_types', function (Blueprint $table) {
            $table->string('entidad', 30)
                ->default('PERSONAL')
                ->after('nombre');
        });

        DB::table('incidence_types')->update([
            'entidad' => 'PERSONAL',
        ]);
    }

    public function down()
    {
        Schema::table('incidence_types', function (Blueprint $table) {
            $table->dropColumn('entidad');
        });
    }
}

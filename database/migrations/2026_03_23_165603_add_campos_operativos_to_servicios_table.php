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
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('municipio')->nullable()->after('lugar');
            $table->string('estatus_servicio')->nullable()->after('tipo_servicio');
            $table->string('oficio_referencia')->nullable()->after('estatus_servicio');
            $table->string('memorandum_referencia')->nullable()->after('oficio_referencia');
            $table->string('unidad_clave')->nullable()->after('memorandum_referencia');
            $table->string('crp')->nullable()->after('unidad_clave');
            $table->string('objetivo_servicio')->nullable()->after('crp');
            $table->text('acciones_realizadas')->nullable()->after('descripcion');
            $table->text('resultados')->nullable()->after('acciones_realizadas');
            $table->text('conclusion_operativa')->nullable()->after('resultados');
            $table->string('comandante_responsable')->nullable()->after('conclusion_operativa');
            $table->string('cargo_responsable')->nullable()->after('comandante_responsable');
            $table->time('hora_fin')->nullable()->after('hora');
            $table->string('folio_operativo')->nullable()->after('hora_fin');
            $table->decimal('lat', 10, 7)->nullable()->after('folio_operativo');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn([
                'municipio',
                'estatus_servicio',
                'oficio_referencia',
                'memorandum_referencia',
                'unidad_clave',
                'crp',
                'objetivo_servicio',
                'acciones_realizadas',
                'resultados',
                'conclusion_operativa',
                'comandante_responsable',
                'cargo_responsable',
                'hora_fin',
                'folio_operativo',
                'lat',
                'lng',
            ]);
        });
    }
};

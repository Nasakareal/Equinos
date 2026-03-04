<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puestas_disposicions', function (Blueprint $table) {

            $table->string('folio', 60)->change();

            if (Schema::hasColumn('puestas_disposicions', 'folio_num')) {
                $table->dropColumn('folio_num');
            }
        });

        $posibles = [
            'pd_anio_folio_unique',
            'puestas_disposicions_anio_folio_unique',
            'anio_folio_unique',
        ];

        foreach ($posibles as $idx) {
            try {
                DB::statement("ALTER TABLE `puestas_disposicions` DROP INDEX `$idx`");
            } catch (\Throwable $e) {
            }
        }

        Schema::table('puestas_disposicions', function (Blueprint $table) {
            $table->unique(['anio', 'folio'], 'pd_anio_folio_unique');
        });
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `puestas_disposicions` DROP INDEX `pd_anio_folio_unique`");
        } catch (\Throwable $e) {
        }

        Schema::table('puestas_disposicions', function (Blueprint $table) {
            $table->string('folio', 20)->change();

            if (!Schema::hasColumn('puestas_disposicions', 'folio_num')) {
                $table->unsignedInteger('folio_num')->default(0);
            }
        });
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CatalogosBaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $turnoTables = ['turnos', 'shifts', 'catalog_turnos'];
        $turnos = [
            ['clave' => 'A', 'nombre' => 'TURNO A'],
            ['clave' => 'B', 'nombre' => 'TURNO B'],
            ['clave' => 'MIXTO', 'nombre' => 'MIXTO'],
        ];

        foreach ($turnoTables as $tbl) {
            if (!Schema::hasTable($tbl)) continue;

            foreach ($turnos as $t) {
                $row = ['created_at' => $now, 'updated_at' => $now];

                if (Schema::hasColumn($tbl, 'clave')) $row['clave'] = $t['clave'];
                if (Schema::hasColumn($tbl, 'nombre')) $row['nombre'] = $t['nombre'];
                if (Schema::hasColumn($tbl, 'descripcion')) $row['descripcion'] = $t['nombre'];

                if (count($row) <= 2) continue;

                $q = DB::table($tbl);
                if (isset($row['clave'])) $q->where('clave', $row['clave']);
                elseif (isset($row['nombre'])) $q->where('nombre', $row['nombre']);
                else continue;

                if (!$q->exists()) DB::table($tbl)->insert($row);
            }

            if ($this->command) {
                $this->command->info("Catálogo turnos sembrado en: {$tbl}");
            }
            break;
        }

        $incTables = ['incidencias', 'incidences', 'catalog_incidencias'];
        $incidencias = [
            ['clave' => 'LABORANDO', 'nombre' => 'LABORANDO'],
            ['clave' => 'FRANCO', 'nombre' => 'FRANCO'],
            ['clave' => 'VACACIONES', 'nombre' => 'VACACIONES'],
            ['clave' => 'LICENCIA_LABORAL', 'nombre' => 'LICENCIA LABORAL'],
        ];

        foreach ($incTables as $tbl) {
            if (!Schema::hasTable($tbl)) continue;

            foreach ($incidencias as $i) {
                $row = ['created_at' => $now, 'updated_at' => $now];

                if (Schema::hasColumn($tbl, 'clave')) $row['clave'] = $i['clave'];
                if (Schema::hasColumn($tbl, 'nombre')) $row['nombre'] = $i['nombre'];
                if (Schema::hasColumn($tbl, 'descripcion')) $row['descripcion'] = $i['nombre'];

                if (count($row) <= 2) continue;

                $q = DB::table($tbl);
                if (isset($row['clave'])) $q->where('clave', $row['clave']);
                elseif (isset($row['nombre'])) $q->where('nombre', $row['nombre']);
                else continue;

                if (!$q->exists()) DB::table($tbl)->insert($row);
            }

            if ($this->command) {
                $this->command->info("Catálogo incidencias sembrado en: {$tbl}");
            }
            break;
        }
    }
}

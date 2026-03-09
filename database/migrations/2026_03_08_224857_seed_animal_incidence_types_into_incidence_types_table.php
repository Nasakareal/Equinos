<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAnimalIncidenceTypesIntoIncidenceTypesTable extends Migration
{
    public function up()
    {
        $now = now();

        $types = [
            [
                'clave' => 'LESION_SERVICIO',
                'nombre' => 'LESIÓN EN SERVICIO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'LESION_ENTRENAMIENTO',
                'nombre' => 'LESIÓN EN ENTRENAMIENTO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'ENFERMEDAD',
                'nombre' => 'ENFERMEDAD',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'BAJA_MEDICA',
                'nombre' => 'BAJA MÉDICA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'COJERA',
                'nombre' => 'COJERA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'COLICO',
                'nombre' => 'CÓLICO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'DESHIDRATACION',
                'nombre' => 'DESHIDRATACIÓN',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'AGOTAMIENTO',
                'nombre' => 'AGOTAMIENTO / FATIGA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'MORDIDA',
                'nombre' => 'MORDIDA A PERSONA O ANIMAL',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#dc3545',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'AGRESIVIDAD',
                'nombre' => 'CONDUCTA AGRESIVA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#fd7e14',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'FUGA',
                'nombre' => 'INTENTO DE FUGA / FUGA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#ffc107',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'EXTRAVIO_TEMPORAL',
                'nombre' => 'EXTRAVÍO TEMPORAL',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#ffc107',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'ACCIDENTE_TRASLADO',
                'nombre' => 'ACCIDENTE EN TRASLADO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#dc3545',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'ACCIDENTE_ENTRENAMIENTO',
                'nombre' => 'ACCIDENTE EN ENTRENAMIENTO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#dc3545',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'REACCION_MEDICAMENTO',
                'nombre' => 'REACCIÓN ADVERSA A MEDICAMENTO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#6f42c1',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'ALERGIA',
                'nombre' => 'ALERGIA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 0,
                'color' => '#20c997',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'PROBLEMA_DIGESTIVO',
                'nombre' => 'PROBLEMA DIGESTIVO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'PROBLEMA_RESPIRATORIO',
                'nombre' => 'PROBLEMA RESPIRATORIO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'PROBLEMA_PIEL',
                'nombre' => 'PROBLEMA DE PIEL / DERMATOLÓGICO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 0,
                'color' => null,
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'ESTRES',
                'nombre' => 'ESTRÉS / ALTERACIÓN DE CONDUCTA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#17a2b8',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'SIN_APETITO',
                'nombre' => 'PÉRDIDA DE APETITO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#17a2b8',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'MUERTE',
                'nombre' => 'FALLECIMIENTO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 1,
                'color' => '#343a40',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'DESAPARICION_EQUIPO',
                'nombre' => 'PÉRDIDA O DAÑO DE EQUIPO ASIGNADO',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 0,
                'color' => '#6610f2',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'OBSERVACION_CONDUCTA',
                'nombre' => 'OBSERVACIÓN DE CONDUCTA',
                'entidad' => 'ANIMAL',
                'afecta_servicio' => 0,
                'color' => '#0dcaf0',
                'activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($types as $type) {
            $exists = DB::table('incidence_types')
                ->where('clave', $type['clave'])
                ->where('entidad', $type['entidad'])
                ->exists();

            if (!$exists) {
                DB::table('incidence_types')->insert($type);
            }
        }
    }

    public function down()
    {
        DB::table('incidence_types')
            ->where('entidad', 'ANIMAL')
            ->whereIn('clave', [
                'LESION_SERVICIO',
                'LESION_ENTRENAMIENTO',
                'ENFERMEDAD',
                'BAJA_MEDICA',
                'COJERA',
                'COLICO',
                'DESHIDRATACION',
                'AGOTAMIENTO',
                'MORDIDA',
                'AGRESIVIDAD',
                'FUGA',
                'EXTRAVIO_TEMPORAL',
                'ACCIDENTE_TRASLADO',
                'ACCIDENTE_ENTRENAMIENTO',
                'REACCION_MEDICAMENTO',
                'ALERGIA',
                'PROBLEMA_DIGESTIVO',
                'PROBLEMA_RESPIRATORIO',
                'PROBLEMA_PIEL',
                'ESTRES',
                'SIN_APETITO',
                'MUERTE',
                'DESAPARICION_EQUIPO',
                'OBSERVACION_CONDUCTA',
            ])
            ->delete();
    }
}

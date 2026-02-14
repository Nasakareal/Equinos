<?php

namespace App\Services;

use Carbon\Carbon;

class TurnoResolver
{
    public static function turnoQueLabora(string $fechaYmd, int $turnoAId, int $turnoBId, string $inicioSemanaA): int
    {
        $fecha = Carbon::parse($fechaYmd)->startOfDay();
        $base  = Carbon::parse($inicioSemanaA)->startOfDay();

        $semanas = $base->diffInWeeks($fecha, false);
        $paridadPar = (abs($semanas) % 2) === 0;

        $dia = (int) $fecha->dayOfWeekIso;

        $diasLmvSdD = in_array($dia, [1, 3, 5, 6, 7], true);
        $diasMj     = in_array($dia, [2, 4], true);

        if ($paridadPar) {
            if ($diasLmvSdD) return $turnoAId;
            if ($diasMj)     return $turnoBId;
        } else {
            if ($diasLmvSdD) return $turnoBId;
            if ($diasMj)     return $turnoAId;
        }

        return $turnoAId;
    }
}

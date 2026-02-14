<?php

namespace App\Services;

use App\Models\ServiceSchedule;
use App\Models\Setting;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TurnoActual
{
    public static function getSetting(): Setting
    {
        $s = Setting::query()->orderBy('id')->first();
        if (!$s) $s = Setting::create([]);
        return $s;
    }

    public static function getTurnoActualId(): ?int
    {
        $s = Setting::query()->orderBy('id')->first();
        return $s ? (int)($s->turno_actual_id ?: 0) ?: null : null;
    }

    public static function getInicioSemanaA(): ?string
    {
        $s = Setting::query()->orderBy('id')->first();
        if (!$s) return null;
        return $s->inicio_semana_a ? (string)$s->inicio_semana_a : null;
    }

    public static function setAnclaHoy(int $turnoHoyId): void
    {
        $s = self::getSetting();

        $turnoAId = (int)(Turno::query()->where('clave', 'A')->value('id') ?: 0);
        $turnoBId = (int)(Turno::query()->where('clave', 'B')->value('id') ?: 0);

        if ($turnoAId <= 0 || $turnoBId <= 0) return;

        $hoy = Carbon::now('America/Mexico_City')->startOfDay();

        $lunes = $hoy->copy()->startOfWeek(Carbon::MONDAY);
        $dia = (int)$hoy->dayOfWeekIso;

        $diasLmvSdD = in_array($dia, [1, 3, 5, 6, 7], true);
        $diasMj     = in_array($dia, [2, 4], true);

        $hoyEsSemanaA = false;

        if ($diasLmvSdD) {
            $hoyEsSemanaA = ((int)$turnoHoyId === $turnoAId);
        } elseif ($diasMj) {
            $hoyEsSemanaA = ((int)$turnoHoyId === $turnoBId);
        } else {
            $hoyEsSemanaA = ((int)$turnoHoyId === $turnoAId);
        }

        $inicioSemanaA = $hoyEsSemanaA ? $lunes : $lunes->copy()->subWeek();

        $s->update([
            'turno_actual_id' => (int)$turnoHoyId,
            'inicio_semana_a' => $inicioSemanaA->toDateString(),
            'actualizado_por' => Auth::id(),
            'actualizado_en'  => Carbon::now('America/Mexico_City'),
        ]);
    }

    public static function resolverTurnoIdEnFecha(string $fechaYmd): ?int
    {
        $turnoAId = (int)(Turno::query()->where('clave', 'A')->value('id') ?: 0);
        $turnoBId = (int)(Turno::query()->where('clave', 'B')->value('id') ?: 0);
        if ($turnoAId <= 0 || $turnoBId <= 0) return null;

        $inicioSemanaA = self::getInicioSemanaA();
        if (empty($inicioSemanaA)) return null;

        return TurnoResolver::turnoQueLabora($fechaYmd, $turnoAId, $turnoBId, $inicioSemanaA);
    }

    public static function syncTurnoActualHoy(): ?int
    {
        $hoy = Carbon::now('America/Mexico_City')->toDateString();
        $turnoCalculadoId = self::resolverTurnoIdEnFecha($hoy);
        if (empty($turnoCalculadoId)) return null;

        $turnoGuardadoId = self::getTurnoActualId();
        if ((int)$turnoGuardadoId !== (int)$turnoCalculadoId) {
            $s = self::getSetting();
            $s->update([
                'turno_actual_id' => (int)$turnoCalculadoId,
                'actualizado_por' => Auth::id(),
                'actualizado_en'  => Carbon::now('America/Mexico_City'),
            ]);
        }

        return (int)$turnoCalculadoId;
    }

    public static function laborandoIds(): array
    {
        $turnoActualId = self::getTurnoActualId();
        if (empty($turnoActualId)) return [];

        $turnoClave = (string)(Turno::query()->where('id', (int)$turnoActualId)->value('clave') ?: '');
        if (!in_array($turnoClave, ['A', 'B'], true)) return [];

        return ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->where('turno_id', (int)$turnoActualId)
            ->pluck('personal_id')
            ->unique()
            ->values()
            ->toArray();
    }

    public static function turnoActual(): ?Turno
    {
        $id = self::getTurnoActualId();
        if (!$id) return null;

        $t = Turno::query()->find((int)$id);
        if (!$t) return null;

        return in_array((string)$t->clave, ['A', 'B'], true) ? $t : null;
    }
}

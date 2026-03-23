<?php

namespace App\Services;

use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Setting;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TurnoActual
{
    private const TZ = 'America/Mexico_City';

    public static function getSetting(): Setting
    {
        $s = Setting::query()->orderBy('id')->first();
        if (!$s) {
            $s = Setting::create([]);
        }
        return $s;
    }

    public static function getTurnoActualId(): ?int
    {
        $s = Setting::query()->orderBy('id')->first();
        return $s ? ((int) ($s->turno_actual_id ?: 0) ?: null) : null;
    }

    public static function getInicioSemanaA(): ?string
    {
        $s = Setting::query()->orderBy('id')->first();
        if (!$s) {
            return null;
        }

        return $s->inicio_semana_a ? (string) $s->inicio_semana_a : null;
    }

    public static function setAnclaHoy(int $turnoHoyId): void
    {
        $s = self::getSetting();

        $turnoAId = (int) (Turno::query()->where('clave', 'A')->value('id') ?: 0);
        $turnoBId = (int) (Turno::query()->where('clave', 'B')->value('id') ?: 0);

        if ($turnoAId <= 0 || $turnoBId <= 0) {
            return;
        }

        if (!in_array((int) $turnoHoyId, [$turnoAId, $turnoBId], true)) {
            return;
        }

        $hoy = Carbon::now(self::TZ)->startOfDay();
        $lunes = $hoy->copy()->startOfWeek(Carbon::MONDAY);

        $dia = (int) $hoy->dayOfWeekIso;
        $diasLmvSdD = in_array($dia, [1, 3, 5, 6, 7], true);
        $diasMj     = in_array($dia, [2, 4], true);

        if ($diasLmvSdD) {
            $hoyEsSemanaA = ((int) $turnoHoyId === $turnoAId);
        } elseif ($diasMj) {
            $hoyEsSemanaA = ((int) $turnoHoyId === $turnoBId);
        } else {
            $hoyEsSemanaA = ((int) $turnoHoyId === $turnoAId);
        }

        $inicioSemanaA = $hoyEsSemanaA ? $lunes : $lunes->copy()->subWeek();

        $s->update([
            'turno_actual_id' => (int) $turnoHoyId,
            'inicio_semana_a' => $inicioSemanaA->toDateString(),
            'actualizado_por' => Auth::id(),
            'actualizado_en'  => Carbon::now(self::TZ),
        ]);
    }

    public static function resolverTurnoIdEnFecha(string $fechaYmd): ?int
    {
        $turnoAId = (int) (Turno::query()->where('clave', 'A')->value('id') ?: 0);
        $turnoBId = (int) (Turno::query()->where('clave', 'B')->value('id') ?: 0);

        if ($turnoAId <= 0 || $turnoBId <= 0) {
            return null;
        }

        $inicioSemanaA = self::getInicioSemanaA();
        if (empty($inicioSemanaA)) {
            return null;
        }

        return TurnoResolver::turnoQueLabora($fechaYmd, $turnoAId, $turnoBId, $inicioSemanaA);
    }

    public static function syncTurnoActualHoy(): ?int
    {
        $hoy = Carbon::now(self::TZ)->toDateString();

        $turnoCalculadoId = self::resolverTurnoIdEnFecha($hoy);
        if (empty($turnoCalculadoId)) {
            return null;
        }

        $turnoGuardadoId = self::getTurnoActualId();

        if ((int) $turnoGuardadoId !== (int) $turnoCalculadoId) {
            $s = self::getSetting();
            $s->update([
                'turno_actual_id' => (int) $turnoCalculadoId,
                'actualizado_por' => Auth::id(),
                'actualizado_en'  => Carbon::now(self::TZ),
            ]);
        }

        return (int) $turnoCalculadoId;
    }

    public static function laborandoIds(?int $areaId = null): array
    {
        $turnoActualId = self::syncTurnoActualHoy();
        if (empty($turnoActualId)) {
            $turnoActualId = self::getTurnoActualId();
        }

        if (empty($turnoActualId)) {
            return [];
        }

        $turnoClave = (string) (Turno::query()->where('id', (int) $turnoActualId)->value('clave') ?: '');
        if (!in_array($turnoClave, ['A', 'B'], true)) {
            return [];
        }

        $queryCiclicos = Personal::query()
            ->where('activo', 1)
            ->where('turno_id', (int) $turnoActualId);

        if (!is_null($areaId)) {
            $queryCiclicos->where('area_id', (int) $areaId);
        }

        $idsCiclicos = $queryCiclicos
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        $queryPersonalizados = Personal::query()
            ->where('activo', 1)
            ->whereIn(
                'id',
                ServiceSchedule::query()
                    ->where('activo', 1)
                    ->where('tipo', 'PERSONALIZADO')
                    ->pluck('personal_id')
                    ->unique()
                    ->values()
                    ->toArray()
            );

        if (!is_null($areaId)) {
            $queryPersonalizados->where('area_id', (int) $areaId);
        }

        $idsPersonalizados = $queryPersonalizados
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        $ids = array_merge($idsCiclicos, $idsPersonalizados);
        $ids = array_values(array_unique(array_map('intval', $ids)));
        sort($ids);

        return $ids;
    }

    public static function siempreVisiblesIds(?int $areaId = null): array
    {
        $query = Personal::query()
            ->where('activo', 1)
            ->where('siempre_visible', 1);

        if (!is_null($areaId)) {
            $query->where('area_id', (int) $areaId);
        }

        return $query
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();
    }

    public static function requeridosIds(?int $areaId = null): array
    {
        $ids = array_merge(
            self::laborandoIds($areaId),
            self::siempreVisiblesIds($areaId)
        );

        $ids = array_values(array_unique(array_map('intval', $ids)));
        sort($ids);

        return $ids;
    }

    public static function turnoActual(): ?Turno
    {
        $id = self::syncTurnoActualHoy();

        if (!$id) {
            $id = self::getTurnoActualId();
        }

        if (!$id) {
            return null;
        }

        $t = Turno::query()->find((int) $id);
        if (!$t) {
            return null;
        }

        return in_array((string) $t->clave, ['A', 'B'], true) ? $t : null;
    }
}

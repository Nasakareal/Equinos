<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\ServiceSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $now = Carbon::now('America/Mexico_City');

        $total_personal = Personal::query()->count();

        $total_por_dependencia = Personal::query()
            ->selectRaw('dependencia, COUNT(*) as total')
            ->groupBy('dependencia')
            ->orderBy('dependencia')
            ->get();

        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if ($this->estaLaborando($sc, $now)) {
                $laborando_ids[] = (int)$sc->personal_id;
            }
        }

        $laborando_ids = array_values(array_unique($laborando_ids));

        $total_laborando = Personal::query()->whereIn('id', $laborando_ids)->count();

        $laborando_por_dependencia = Personal::query()
            ->selectRaw('dependencia, COUNT(*) as total')
            ->whereIn('id', $laborando_ids)
            ->groupBy('dependencia')
            ->orderBy('dependencia')
            ->get();

        return view('home', compact(
            'total_personal',
            'total_por_dependencia',
            'total_laborando',
            'laborando_por_dependencia',
            'now'
        ));
    }

    private function estaLaborando($sc, Carbon $now): bool
    {
        $inicio = Carbon::parse($sc->fecha_inicio_ciclo, 'America/Mexico_City')->setTime(7, 0, 0);

        if ($now->lt($inicio)) return false;

        $horas_trabajo = (int)$sc->horas_trabajo;
        $horas_descanso = (int)$sc->horas_descanso;

        if ($horas_trabajo <= 0) return false;
        if ($horas_descanso < 0) $horas_descanso = 0;

        $ciclo = $horas_trabajo + $horas_descanso;
        if ($ciclo <= 0) return false;

        $diffHoras = $inicio->diffInHours($now);
        $pos = $diffHoras % $ciclo;

        return $pos < $horas_trabajo;
    }
}

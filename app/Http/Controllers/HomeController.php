<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Services\TurnoActual;
use Carbon\Carbon;

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

        $turno_en_servicio_id = TurnoActual::syncTurnoActualHoy();

        $turno_actual = TurnoActual::turnoActual();

        $laborando_ids = TurnoActual::laborandoIds();

        $total_laborando = empty($laborando_ids)
            ? 0
            : Personal::query()->whereIn('id', $laborando_ids)->count();

        $laborando_por_dependencia = empty($laborando_ids)
            ? collect()
            : Personal::query()
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
            'now',
            'turno_en_servicio_id',
            'turno_actual'
        ));
    }
}

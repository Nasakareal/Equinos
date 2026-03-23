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

        $total_por_area = Personal::query()
            ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
            ->selectRaw('COALESCE(areas.nombre, "Sin área") as area, COUNT(personals.id) as total')
            ->groupBy('areas.nombre')
            ->orderBy('areas.nombre')
            ->get();

        $turno_en_servicio_id = TurnoActual::syncTurnoActualHoy();

        $turno_actual = TurnoActual::turnoActual();

        $laborando_ids = TurnoActual::laborandoIds();

        $total_laborando = empty($laborando_ids)
            ? 0
            : Personal::query()->whereIn('id', $laborando_ids)->count();

        $laborando_por_area = empty($laborando_ids)
            ? collect()
            : Personal::query()
                ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
                ->selectRaw('COALESCE(areas.nombre, "Sin área") as area, COUNT(personals.id) as total')
                ->whereIn('personals.id', $laborando_ids)
                ->groupBy('areas.nombre')
                ->orderBy('areas.nombre')
                ->get();

        return view('home', compact(
            'total_personal',
            'total_por_area',
            'total_laborando',
            'laborando_por_area',
            'now',
            'turno_en_servicio_id',
            'turno_actual'
        ));
    }
}

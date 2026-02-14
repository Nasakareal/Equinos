<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Services\TurnoActual;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function turnoActual()
    {
        $turnos = Turno::query()
            ->where('activo', 1)
            ->whereIn('clave', ['A', 'B'])
            ->orderBy('id')
            ->get();

        $turno_actual_id = TurnoActual::getTurnoActualId();

        if (!empty($turno_actual_id) && !$turnos->contains('id', (int)$turno_actual_id)) {
            $turno_actual_id = null;
        }

        return view('admin.settings.turno_actual', compact('turnos', 'turno_actual_id'));
    }

    public function updateTurnoActual(Request $request)
    {
        $turnosPermitidos = Turno::query()
            ->where('activo', 1)
            ->whereIn('clave', ['A', 'B'])
            ->pluck('id')
            ->map(fn ($id) => (int)$id)
            ->toArray();

        $request->validate([
            'turno_actual_id' => ['required', 'integer', 'in:' . implode(',', $turnosPermitidos)],
        ]);

        TurnoActual::setAnclaHoy((int)$request->turno_actual_id);

        return redirect()
            ->route('settings.turno_actual')
            ->with('success', 'Turno en servicio actualizado.');
    }
}

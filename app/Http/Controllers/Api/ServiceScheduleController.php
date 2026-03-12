<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use App\Models\TurnoHorario;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    public function index()
    {
        return response()->json(['ok' => true, 'data' => TurnoHorario::with('turno')->orderByDesc('id')->get()]);
    }

    public function catalogos()
    {
        return response()->json(['ok' => true, 'data' => ['turnos' => Turno::orderBy('nombre')->get()]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'min_tolerancia' => 'required|integer|min:0|max:1440',
            'cruza_dia' => 'nullable|boolean',
            'notas' => 'nullable|string|max:255',
        ]);

        $item = TurnoHorario::create([
            'turno_id' => $validated['turno_id'],
            'hora_entrada' => $validated['hora_entrada'] ?? null,
            'hora_salida' => $validated['hora_salida'] ?? null,
            'min_tolerancia' => $validated['min_tolerancia'] ?? 0,
            'cruza_dia' => !empty($validated['cruza_dia']) ? 1 : 0,
            'notas' => $validated['notas'] ?? null,
        ]);

        return response()->json(['ok' => true, 'message' => 'Horario de servicio creado correctamente.', 'data' => $item->load('turno')], 201);
    }

    public function show(TurnoHorario $service_schedule)
    {
        return response()->json(['ok' => true, 'data' => $service_schedule->load('turno')]);
    }

    public function update(Request $request, TurnoHorario $service_schedule)
    {
        $validated = $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'min_tolerancia' => 'required|integer|min:0|max:1440',
            'cruza_dia' => 'nullable|boolean',
            'notas' => 'nullable|string|max:255',
        ]);

        $service_schedule->update([
            'turno_id' => $validated['turno_id'],
            'hora_entrada' => $validated['hora_entrada'] ?? null,
            'hora_salida' => $validated['hora_salida'] ?? null,
            'min_tolerancia' => $validated['min_tolerancia'] ?? 0,
            'cruza_dia' => !empty($validated['cruza_dia']) ? 1 : 0,
            'notas' => $validated['notas'] ?? null,
        ]);

        return response()->json(['ok' => true, 'message' => 'Horario de servicio actualizado correctamente.', 'data' => $service_schedule->fresh()->load('turno')]);
    }

    public function destroy(TurnoHorario $service_schedule)
    {
        $service_schedule->delete();
        return response()->json(['ok' => true, 'message' => 'Horario de servicio eliminado correctamente.']);
    }
}

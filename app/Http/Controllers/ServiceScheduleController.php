<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\TurnoHorario;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    /**
     * Listado de horarios de servicio
     */
    public function index()
    {
        $service_schedules = TurnoHorario::with('turno')
            ->orderBy('id', 'desc')
            ->get();

        return view('servicio.index', compact('service_schedules'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $turnos = Turno::orderBy('nombre')->get();

        return view('servicio.create', compact('turnos'));
    }

    /**
     * Guardar horario de servicio
     */
    public function store(Request $request)
    {
        $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'min_tolerancia' => 'required|integer|min:0|max:1440',
            'cruza_dia' => 'nullable|in:0,1',
            'notas' => 'nullable|string|max:255',
        ]);

        TurnoHorario::create([
            'turno_id' => $request->turno_id,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'min_tolerancia' => $request->min_tolerancia ?? 0,
            'cruza_dia' => $request->has('cruza_dia') ? 1 : 0,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('servicio.index')
            ->with('success', 'Horario de servicio creado correctamente');
    }

    /**
     * Mostrar horario de servicio
     */
    public function show(TurnoHorario $service_schedule)
    {
        $service_schedule->load('turno');

        return view('servicio.show', compact('service_schedule'));
    }

    /**
     * Formulario de edición
     */
    public function edit(TurnoHorario $service_schedule)
    {
        $turnos = Turno::orderBy('nombre')->get();

        return view('servicio.edit', compact('service_schedule', 'turnos'));
    }

    /**
     * Actualizar horario de servicio
     */
    public function update(Request $request, TurnoHorario $service_schedule)
    {
        $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'min_tolerancia' => 'required|integer|min:0|max:1440',
            'cruza_dia' => 'nullable|in:0,1',
            'notas' => 'nullable|string|max:255',
        ]);

        $service_schedule->update([
            'turno_id' => $request->turno_id,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'min_tolerancia' => $request->min_tolerancia ?? 0,
            'cruza_dia' => $request->has('cruza_dia') ? 1 : 0,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('servicio.index')
            ->with('success', 'Horario de servicio actualizado correctamente');
    }

    /**
     * Eliminar horario de servicio
     */
    public function destroy(TurnoHorario $service_schedule)
    {
        $service_schedule->delete();

        return redirect()
            ->route('servicio.index')
            ->with('success', 'Horario de servicio eliminado correctamente');
    }
}

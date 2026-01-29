<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\TurnoHorario;
use Illuminate\Http\Request;

class TurnoHorarioController extends Controller
{
    /**
     * Listado de turnos-horarios
     */
    public function index()
    {
        $turno_horarios = TurnoHorario::with('turno')
            ->orderBy('id', 'desc')
            ->get();

        return view('turno_horarios.index', compact('turno_horarios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $turnos = Turno::orderBy('nombre')->get();

        return view('turno_horarios.create', compact('turnos'));
    }

    /**
     * Guardar nuevo turno-horario
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
            ->route('turno_horarios.index')
            ->with('success', 'Horario del turno creado correctamente');
    }

    /**
     * Mostrar turno-horario
     */
    public function show(TurnoHorario $turno_horario)
    {
        $turno_horario->load('turno');

        return view('turno_horarios.show', compact('turno_horario'));
    }

    /**
     * Formulario de edición
     */
    public function edit(TurnoHorario $turno_horario)
    {
        $turnos = Turno::orderBy('nombre')->get();

        return view('turno_horarios.edit', compact('turno_horario', 'turnos'));
    }

    /**
     * Actualizar turno-horario
     */
    public function update(Request $request, TurnoHorario $turno_horario)
    {
        $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'min_tolerancia' => 'required|integer|min:0|max:1440',
            'cruza_dia' => 'nullable|in:0,1',
            'notas' => 'nullable|string|max:255',
        ]);

        $turno_horario->update([
            'turno_id' => $request->turno_id,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'min_tolerancia' => $request->min_tolerancia ?? 0,
            'cruza_dia' => $request->has('cruza_dia') ? 1 : 0,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('turno_horarios.index')
            ->with('success', 'Horario del turno actualizado correctamente');
    }

    /**
     * Eliminar turno-horario
     */
    public function destroy(TurnoHorario $turno_horario)
    {
        $turno_horario->delete();

        return redirect()
            ->route('turno_horarios.index')
            ->with('success', 'Horario del turno eliminado correctamente');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    /**
     * Listado de turnos
     */
    public function index()
    {
        $turnos = Turno::orderBy('id')->get();
        return view('turnos.index', compact('turnos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('turnos.create');
    }

    /**
     * Guardar nuevo turno
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:20|unique:turnos,clave',
            'nombre' => 'required|string|max:100|unique:turnos,nombre',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|in:0,1',
        ]);

        Turno::create([
            'clave' => $request->clave,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
        ]);

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno creado correctamente');
    }

    /**
     * Mostrar turno
     */
    public function show(Turno $turno)
    {
        return view('turnos.show', compact('turno'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Turno $turno)
    {
        return view('turnos.edit', compact('turno'));
    }

    /**
     * Actualizar turno
     */
    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'clave' => 'required|string|max:20|unique:turnos,clave,' . $turno->id,
            'nombre' => 'required|string|max:100|unique:turnos,nombre,' . $turno->id,
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|in:0,1',
        ]);

        $turno->update([
            'clave' => $request->clave,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
        ]);

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno actualizado correctamente');
    }

    /**
     * Eliminar turno
     */
    public function destroy(Turno $turno)
    {
        $turno->delete();

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno eliminado correctamente');
    }
}

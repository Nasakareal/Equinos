<?php

namespace App\Http\Controllers;

use App\Models\Patrol;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function index()
    {
        $patrols = Patrol::orderByRaw("
            CASE
                WHEN tipo = 'EQUINO' THEN 1
                WHEN tipo = 'CANINO' THEN 2
                WHEN tipo = 'RAM' THEN 3
                WHEN tipo = 'LOGISTICA' THEN 4
                ELSE 5
            END
        ")->orderBy('numero_economico')->get();

        return view('patrullas.index', compact('patrols'));
    }

    public function create()
    {
        return view('patrullas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_economico' => 'required|string|max:255|unique:patrols,numero_economico',
            'tipo' => 'required|string|max:255',
            'placas' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'anio' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'estado' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        Patrol::create($data);

        return redirect()->route('patrullas.index')
            ->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Patrol $patrol)
    {
        $patrol->load(['assignments.turno', 'assignments.creador', 'assignments.personals']);

        return view('patrullas.show', compact('patrol'));
    }

    public function edit(Patrol $patrol)
    {
        return view('patrullas.edit', compact('patrol'));
    }

    public function update(Request $request, Patrol $patrol)
    {
        $data = $request->validate([
            'numero_economico' => 'required|string|max:255|unique:patrols,numero_economico,' . $patrol->id,
            'tipo' => 'required|string|max:255',
            'placas' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'anio' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'estado' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $patrol->update($data);

        return redirect()->route('patrullas.index')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Patrol $patrol)
    {
        $patrol->delete();

        return redirect()->route('patrullas.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}

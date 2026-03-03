<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalAssignment;
use App\Models\Personal;
use App\Models\Patrol;
use App\Models\Turno;
use Illuminate\Http\Request;

class AnimalAssignmentController extends Controller
{
    public function index(Animal $animal)
    {
        $assignments = $animal->assignments()
            ->with(['personal', 'patrol', 'turno'])
            ->orderByDesc('inicio')
            ->get();

        return view('animales.asignaciones.index', compact('animal', 'assignments'));
    }

    public function create(Animal $animal)
    {
        $personals = Personal::orderBy('nombre')->get();
        $patrols = Patrol::orderBy('numero_economico')->get();
        $turnos = Turno::orderBy('nombre')->get();

        return view('animales.asignaciones.create', compact('animal', 'personals', 'patrols', 'turnos'));
    }

    public function store(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'personal_id' => 'nullable|exists:personals,id',
            'patrol_id' => 'nullable|exists:patrols,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'inicio' => 'required|date',
            'fin' => 'nullable|date|after_or_equal:inicio',
            'observaciones' => 'nullable|string'
        ]);

        $validated['animal_id'] = $animal->id;

        AnimalAssignment::create($validated);

        return redirect()->route('animales.asignaciones.index', $animal)
            ->with('success', 'Asignación registrada correctamente');
    }

    public function destroy(Animal $animal, AnimalAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('animales.asignaciones.index', $animal)
            ->with('success', 'Asignación eliminada correctamente');
    }
}

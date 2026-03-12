<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalAssignment;
use App\Models\Patrol;
use App\Models\Personal;
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

        return response()->json([
            'ok' => true,
            'data' => $assignments,
        ]);
    }

    public function catalogos(Animal $animal)
    {
        return response()->json([
            'ok' => true,
            'data' => [
                'animal' => $animal,
                'personals' => Personal::query()->orderBy('nombres')->get(),
                'patrols' => Patrol::query()->orderBy('numero_economico')->get(),
                'turnos' => Turno::query()->orderBy('nombre')->get(),
            ],
        ]);
    }

    public function store(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'personal_id' => 'nullable|exists:personals,id',
            'patrol_id' => 'nullable|exists:patrols,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'inicio' => 'required|date',
            'fin' => 'nullable|date|after_or_equal:inicio',
            'observaciones' => 'nullable|string',
        ]);

        $validated['animal_id'] = $animal->id;

        $assignment = AnimalAssignment::create($validated);
        $assignment->load(['personal', 'patrol', 'turno']);

        return response()->json([
            'ok' => true,
            'message' => 'Asignación registrada correctamente.',
            'data' => $assignment,
        ], 201);
    }

    public function show(Animal $animal, AnimalAssignment $assignment)
    {
        if ((int) $assignment->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $assignment->load(['personal', 'patrol', 'turno']);

        return response()->json([
            'ok' => true,
            'data' => $assignment,
        ]);
    }

    public function update(Request $request, Animal $animal, AnimalAssignment $assignment)
    {
        if ((int) $assignment->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $validated = $request->validate([
            'personal_id' => 'nullable|exists:personals,id',
            'patrol_id' => 'nullable|exists:patrols,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'inicio' => 'required|date',
            'fin' => 'nullable|date|after_or_equal:inicio',
            'observaciones' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return response()->json([
            'ok' => true,
            'message' => 'Asignación actualizada correctamente.',
            'data' => $assignment->fresh()->load(['personal', 'patrol', 'turno']),
        ]);
    }

    public function destroy(Animal $animal, AnimalAssignment $assignment)
    {
        if ((int) $assignment->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $assignment->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Asignación eliminada correctamente.',
        ]);
    }
}

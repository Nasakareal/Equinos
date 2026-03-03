<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function index(Request $request)
    {
        $patrols = Patrol::query()
            ->orderByRaw("
                CASE
                    WHEN tipo = 'EQUINO' THEN 1
                    WHEN tipo = 'CANINO' THEN 2
                    WHEN tipo = 'RAM' THEN 3
                    WHEN tipo = 'LOGISTICA' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('numero_economico')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $patrols,
        ]);
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

        $patrol = Patrol::create($data);

        return response()->json([
            'ok' => true,
            'message' => 'Unidad registrada correctamente.',
            'data' => $patrol,
        ], 201);
    }

    public function show(Patrol $patrol)
    {
        $patrol->load(['assignments.turno', 'assignments.creador', 'assignments.personals']);

        return response()->json([
            'ok' => true,
            'data' => $patrol,
        ]);
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

        $patrol->refresh();

        return response()->json([
            'ok' => true,
            'message' => 'Unidad actualizada correctamente.',
            'data' => $patrol,
        ]);
    }

    public function destroy(Patrol $patrol)
    {
        $patrol->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Unidad eliminada correctamente.',
        ]);
    }
}

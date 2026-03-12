<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalIncidence;
use App\Models\IncidenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnimalIncidenceController extends Controller
{
    public function index(Animal $animal)
    {
        $incidences = $animal->incidences()
            ->with(['incidenceType', 'atendidoPor', 'files'])
            ->orderByDesc('fecha')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $incidences,
        ]);
    }

    public function catalogos(Animal $animal)
    {
        $types = IncidenceType::query()
            ->where('activo', 1)
            ->where(function ($q) {
                $q->where('entidad', 'ANIMAL')->orWhereNull('entidad');
            })
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'animal' => $animal,
                'incidence_types' => $types,
            ],
        ]);
    }

    public function store(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'incidence_type_id' => 'nullable|exists:incidence_types,id',
            'gravedad' => 'required|in:BAJA,MEDIA,ALTA',
            'descripcion' => 'nullable|string',
            'resuelto' => 'nullable|boolean',
            'resuelto_en' => 'nullable|date',
        ]);

        if (!empty($validated['incidence_type_id'])) {
            $type = IncidenceType::query()
                ->where('id', $validated['incidence_type_id'])
                ->where('activo', 1)
                ->where(function ($q) {
                    $q->where('entidad', 'ANIMAL')->orWhereNull('entidad');
                })
                ->first();

            if (!$type) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El tipo de incidencia seleccionado no corresponde a animales.',
                ], 422);
            }
        }

        $incidence = AnimalIncidence::create([
            'animal_id' => $animal->id,
            'fecha' => $validated['fecha'],
            'incidence_type_id' => $validated['incidence_type_id'] ?? null,
            'gravedad' => $validated['gravedad'],
            'descripcion' => $validated['descripcion'] ?? null,
            'atendido_por' => Auth::id(),
            'resuelto' => (bool) ($validated['resuelto'] ?? false),
            'resuelto_en' => $validated['resuelto_en'] ?? null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Incidencia registrada correctamente.',
            'data' => $incidence->load(['incidenceType', 'atendidoPor', 'files']),
        ], 201);
    }

    public function show(Animal $animal, AnimalIncidence $incidence)
    {
        if ((int) $incidence->animal_id !== (int) $animal->id) {
            abort(404);
        }

        return response()->json([
            'ok' => true,
            'data' => $incidence->load(['incidenceType', 'atendidoPor', 'files']),
        ]);
    }

    public function update(Request $request, Animal $animal, AnimalIncidence $incidence)
    {
        if ((int) $incidence->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $validated = $request->validate([
            'fecha' => 'required|date',
            'incidence_type_id' => 'nullable|exists:incidence_types,id',
            'gravedad' => 'required|in:BAJA,MEDIA,ALTA',
            'descripcion' => 'nullable|string',
            'resuelto' => 'nullable|boolean',
            'resuelto_en' => 'nullable|date',
        ]);

        if (!empty($validated['incidence_type_id'])) {
            $type = IncidenceType::query()
                ->where('id', $validated['incidence_type_id'])
                ->where('activo', 1)
                ->where(function ($q) {
                    $q->where('entidad', 'ANIMAL')->orWhereNull('entidad');
                })
                ->first();

            if (!$type) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El tipo de incidencia seleccionado no corresponde a animales.',
                ], 422);
            }
        }

        $incidence->update([
            'fecha' => $validated['fecha'],
            'incidence_type_id' => $validated['incidence_type_id'] ?? null,
            'gravedad' => $validated['gravedad'],
            'descripcion' => $validated['descripcion'] ?? null,
            'resuelto' => (bool) ($validated['resuelto'] ?? false),
            'resuelto_en' => $validated['resuelto_en'] ?? null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Incidencia actualizada correctamente.',
            'data' => $incidence->fresh()->load(['incidenceType', 'atendidoPor', 'files']),
        ]);
    }

    public function destroy(Animal $animal, AnimalIncidence $incidence)
    {
        if ((int) $incidence->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $incidence->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Incidencia eliminada correctamente.',
        ]);
    }
}

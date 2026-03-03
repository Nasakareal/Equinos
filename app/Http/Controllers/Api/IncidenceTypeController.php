<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncidenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class IncidenceTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = IncidenceType::query()->orderBy('nombre');

        if ($request->filled('activo')) {
            $q->where('activo', (int) $request->query('activo'));
        }

        $incidence_types = $q->get();

        return response()->json([
            'ok' => true,
            'data' => $incidence_types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave' => ['required', 'string', 'max:60', Rule::unique('incidence_types', 'clave')],
            'nombre' => ['required', 'string', 'max:120', Rule::unique('incidence_types', 'nombre')],
            'afecta_servicio' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:30'],
            'activo' => ['nullable', 'boolean'],
        ]);

        try {
            $type = IncidenceType::create([
                'clave' => strtoupper(trim((string) $validated['clave'])),
                'nombre' => strtoupper(trim((string) $validated['nombre'])),
                'afecta_servicio' => array_key_exists('afecta_servicio', $validated) ? (int) (bool) $validated['afecta_servicio'] : 1,
                'color' => array_key_exists('color', $validated) ? trim((string) $validated['color']) : null,
                'activo' => array_key_exists('activo', $validated) ? (int) (bool) $validated['activo'] : 1,
            ]);

            Log::info("Tipo de incidencia creado: {$type->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Tipo de incidencia creado correctamente.',
                'data' => $type,
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error al crear tipo de incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el tipo de incidencia.',
            ], 500);
        }
    }

    public function show(IncidenceType $incidence_type)
    {
        return response()->json([
            'ok' => true,
            'data' => $incidence_type,
        ]);
    }

    public function update(Request $request, IncidenceType $incidence_type)
    {
        $validated = $request->validate([
            'clave' => ['required', 'string', 'max:60', Rule::unique('incidence_types', 'clave')->ignore($incidence_type->id)],
            'nombre' => ['required', 'string', 'max:120', Rule::unique('incidence_types', 'nombre')->ignore($incidence_type->id)],
            'afecta_servicio' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:30'],
            'activo' => ['nullable', 'boolean'],
        ]);

        try {
            $incidence_type->update([
                'clave' => strtoupper(trim((string) $validated['clave'])),
                'nombre' => strtoupper(trim((string) $validated['nombre'])),
                'afecta_servicio' => array_key_exists('afecta_servicio', $validated) ? (int) (bool) $validated['afecta_servicio'] : 1,
                'color' => array_key_exists('color', $validated) ? trim((string) $validated['color']) : null,
                'activo' => array_key_exists('activo', $validated) ? (int) (bool) $validated['activo'] : 1,
            ]);

            Log::info("Tipo de incidencia actualizado: {$incidence_type->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Tipo de incidencia actualizado correctamente.',
                'data' => $incidence_type->fresh(),
            ]);
        } catch (\Exception $e) {
            Log::error("Error al actualizar tipo de incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el tipo de incidencia.',
            ], 500);
        }
    }

    public function destroy(IncidenceType $incidence_type)
    {
        try {
            $id = $incidence_type->id;
            $incidence_type->delete();

            Log::info("Tipo de incidencia eliminado: {$id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Tipo de incidencia eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error("Error al eliminar tipo de incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el tipo de incidencia.',
            ], 500);
        }
    }
}

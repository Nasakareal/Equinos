<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use App\Models\IncidenceType;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IncidenceController extends Controller
{
    public function index(Request $request)
    {
        $q = Incidence::query()
            ->with(['tipo', 'personal', 'registradoPor'])
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('created_at');

        if ($request->filled('personal_id')) {
            $q->where('personal_id', (int) $request->query('personal_id'));
        }

        if ($request->filled('incidence_type_id')) {
            $q->where('incidence_type_id', (int) $request->query('incidence_type_id'));
        }

        if ($request->filled('desde')) {
            $q->whereDate('fecha_inicio', '>=', $request->query('desde'));
        }

        if ($request->filled('hasta')) {
            $q->whereDate('fecha_inicio', '<=', $request->query('hasta'));
        }

        $incidencias = $q->get();

        return response()->json([
            'ok' => true,
            'data' => $incidencias,
        ]);
    }

    public function catalogos(Request $request)
    {
        $personal_id_preseleccionado = $request->query('personal_id');

        if (!empty($personal_id_preseleccionado)) {
            $existe = Personal::query()->where('id', $personal_id_preseleccionado)->exists();
            if (!$existe) $personal_id_preseleccionado = null;
        }

        $incidence_types = IncidenceType::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $personals = Personal::query()
            ->orderBy('nombres')
            ->get();

        return response()->json([
            'ok' => true,
            'incidence_types' => $incidence_types,
            'personals' => $personals,
            'personal_id_preseleccionado' => $personal_id_preseleccionado,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => ['required', 'exists:personals,id'],
            'incidence_type_id' => ['required', 'exists:incidence_types,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'comentario' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $incidence = Incidence::create([
                'personal_id' => $validated['personal_id'],
                'incidence_type_id' => $validated['incidence_type_id'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'] ?? null,
                'comentario' => array_key_exists('comentario', $validated) ? trim((string) $validated['comentario']) : null,
                'registrado_por' => Auth::id(),
            ]);

            $incidence->load(['tipo', 'personal', 'registradoPor']);

            Log::info("Incidencia creada: {$incidence->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Incidencia registrada correctamente.',
                'data' => $incidence,
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error al crear incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al registrar la incidencia.',
            ], 500);
        }
    }

    public function show(Incidence $incidence)
    {
        $incidence->load(['tipo', 'personal', 'registradoPor']);

        return response()->json([
            'ok' => true,
            'data' => $incidence,
        ]);
    }

    public function update(Request $request, Incidence $incidence)
    {
        $validated = $request->validate([
            'personal_id' => ['required', 'exists:personals,id'],
            'incidence_type_id' => ['required', 'exists:incidence_types,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'comentario' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $incidence->update([
                'personal_id' => $validated['personal_id'],
                'incidence_type_id' => $validated['incidence_type_id'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'] ?? null,
                'comentario' => array_key_exists('comentario', $validated) ? trim((string) $validated['comentario']) : null,
            ]);

            $incidence->load(['tipo', 'personal', 'registradoPor']);

            Log::info("Incidencia actualizada: {$incidence->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Incidencia actualizada correctamente.',
                'data' => $incidence,
            ]);
        } catch (\Exception $e) {
            Log::error("Error al actualizar incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar la incidencia.',
            ], 500);
        }
    }

    public function destroy(Incidence $incidence)
    {
        try {
            $id = $incidence->id;
            $incidence->delete();

            Log::info("Incidencia eliminada: {$id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Incidencia eliminada correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error("Error al eliminar incidencia: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar la incidencia.',
            ], 500);
        }
    }
}

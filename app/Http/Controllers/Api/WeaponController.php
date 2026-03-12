<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Weapon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WeaponController extends Controller
{
    public function index()
    {
        $weapons = Weapon::query()
            ->with(['assignments' => function ($q) {
                $q->with('personal')->orderByDesc('fecha_asignacion');
            }])
            ->orderBy('tipo')
            ->orderBy('matricula')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $weapons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:20', Rule::in(['CORTA', 'LARGA'])],
            'marca_modelo' => ['nullable', 'string', 'max:255'],
            'matricula' => ['required', 'string', 'max:80', Rule::unique('weapons', 'matricula')],
            'estado' => ['required', 'string', 'max:30', Rule::in(['ACTIVA', 'INACTIVA', 'BAJA'])],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $weapon = Weapon::create([
                'tipo' => strtoupper(trim($validated['tipo'])),
                'marca_modelo' => isset($validated['marca_modelo']) ? trim($validated['marca_modelo']) : null,
                'matricula' => trim($validated['matricula']),
                'estado' => strtoupper(trim($validated['estado'])),
                'observaciones' => isset($validated['observaciones']) ? trim($validated['observaciones']) : null,
            ]);

            Log::info("API Armamento creado: {$weapon->id} {$weapon->matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Arma creada correctamente.',
                'data' => $weapon,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('API Error al crear arma: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al crear el arma.',
            ], 500);
        }
    }

    public function show(Weapon $weapon)
    {
        $weapon->load([
            'assignments' => function ($q) {
                $q->with('personal')->orderByDesc('fecha_asignacion');
            },
        ]);

        return response()->json([
            'ok' => true,
            'data' => $weapon,
        ]);
    }

    public function update(Request $request, Weapon $weapon)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:20', Rule::in(['CORTA', 'LARGA'])],
            'marca_modelo' => ['nullable', 'string', 'max:255'],
            'matricula' => ['required', 'string', 'max:80', Rule::unique('weapons', 'matricula')->ignore($weapon->id)],
            'estado' => ['required', 'string', 'max:30', Rule::in(['ACTIVA', 'INACTIVA', 'BAJA'])],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $weapon->update([
                'tipo' => strtoupper(trim($validated['tipo'])),
                'marca_modelo' => isset($validated['marca_modelo']) ? trim($validated['marca_modelo']) : null,
                'matricula' => trim($validated['matricula']),
                'estado' => strtoupper(trim($validated['estado'])),
                'observaciones' => isset($validated['observaciones']) ? trim($validated['observaciones']) : null,
            ]);

            Log::info("API Armamento actualizado: {$weapon->id} {$weapon->matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Arma actualizada correctamente.',
                'data' => $weapon->fresh(),
            ]);
        } catch (\Throwable $e) {
            Log::error('API Error al actualizar arma: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al actualizar el arma.',
            ], 500);
        }
    }

    public function destroy(Weapon $weapon)
    {
        try {
            $active = $weapon->assignments()
                ->whereNull('fecha_devolucion')
                ->whereIn('status', ['ASIGNADA', 'ASIGNADO'])
                ->exists();

            if ($active) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se puede eliminar: el arma está asignada actualmente.',
                ], 422);
            }

            $matricula = $weapon->matricula;
            $id = $weapon->id;
            $weapon->delete();

            Log::info("API Armamento eliminado: {$id} {$matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Arma eliminada correctamente.',
            ]);
        } catch (\Throwable $e) {
            Log::error('API Error al eliminar arma: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al eliminar el arma.',
            ], 500);
        }
    }
}

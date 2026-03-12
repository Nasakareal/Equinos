<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\Weapon;
use App\Models\WeaponAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WeaponAssignmentController extends Controller
{
    private function armasDisponiblesQuery()
    {
        return Weapon::query()
            ->whereDoesntHave('assignments', function ($q) {
                $q->whereNull('fecha_devolucion')
                    ->where('status', 'ASIGNADA');
            });
    }

    public function index()
    {
        $weaponAssignments = WeaponAssignment::query()
            ->with(['weapon', 'personal'])
            ->orderByDesc('fecha_asignacion')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $weaponAssignments,
        ]);
    }

    public function catalogos(Request $request)
    {
        $personalId = $request->query('personal_id');

        if (!empty($personalId) && !Personal::query()->where('id', $personalId)->exists()) {
            $personalId = null;
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'weapons' => $this->armasDisponiblesQuery()->orderBy('tipo')->orderBy('matricula')->get(),
                'personals' => Personal::query()->orderBy('nombres')->get(),
                'personal_id_preseleccionado' => $personalId,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => ['required', 'exists:personals,id'],
            'weapon_id' => ['required', 'exists:weapons,id'],
            'fecha_asignacion' => ['nullable', 'date'],
            'fecha_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_asignacion'],
            'status' => ['required', 'string', 'max:30', Rule::in(['ASIGNADA', 'DEVUELTA', 'CANCELADA'])],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = strtoupper(trim($validated['status']));
        $devolucion = $validated['fecha_devolucion'] ?? null;

        if ($status === 'ASIGNADA' && empty($devolucion)) {
            $existsActive = WeaponAssignment::query()
                ->where('weapon_id', $validated['weapon_id'])
                ->whereNull('fecha_devolucion')
                ->where('status', 'ASIGNADA')
                ->exists();

            if ($existsActive) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Esa arma ya está asignada. Primero registra la devolución.',
                ], 422);
            }
        }

        try {
            $assignment = WeaponAssignment::create([
                'personal_id' => $validated['personal_id'],
                'weapon_id' => $validated['weapon_id'],
                'fecha_asignacion' => $validated['fecha_asignacion'] ?? now(),
                'fecha_devolucion' => $validated['fecha_devolucion'] ?? null,
                'status' => $status,
                'observaciones' => isset($validated['observaciones']) ? trim($validated['observaciones']) : null,
            ]);

            $assignment->load(['weapon', 'personal']);

            Log::info("API Armamento asignación creada: {$assignment->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Asignación registrada correctamente.',
                'data' => $assignment,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('API Error al crear asignación: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al registrar la asignación.',
            ], 500);
        }
    }

    public function show(WeaponAssignment $weapon_assignment)
    {
        $weapon_assignment->load(['weapon', 'personal']);

        return response()->json([
            'ok' => true,
            'data' => $weapon_assignment,
        ]);
    }

    public function update(Request $request, WeaponAssignment $weapon_assignment)
    {
        $validated = $request->validate([
            'personal_id' => ['required', 'exists:personals,id'],
            'weapon_id' => ['required', 'exists:weapons,id'],
            'fecha_asignacion' => ['nullable', 'date'],
            'fecha_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_asignacion'],
            'status' => ['required', 'string', 'max:30', Rule::in(['ASIGNADA', 'DEVUELTA', 'CANCELADA'])],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = strtoupper(trim($validated['status']));
        $devolucion = $validated['fecha_devolucion'] ?? null;

        if ($status === 'ASIGNADA' && empty($devolucion)) {
            $existsActive = WeaponAssignment::query()
                ->where('weapon_id', $validated['weapon_id'])
                ->where('id', '!=', $weapon_assignment->id)
                ->whereNull('fecha_devolucion')
                ->where('status', 'ASIGNADA')
                ->exists();

            if ($existsActive) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Esa arma ya está asignada en otro registro activo.',
                ], 422);
            }
        }

        try {
            $weapon_assignment->update([
                'personal_id' => $validated['personal_id'],
                'weapon_id' => $validated['weapon_id'],
                'fecha_asignacion' => $validated['fecha_asignacion'] ?? $weapon_assignment->fecha_asignacion,
                'fecha_devolucion' => $validated['fecha_devolucion'] ?? null,
                'status' => $status,
                'observaciones' => isset($validated['observaciones']) ? trim($validated['observaciones']) : null,
            ]);

            Log::info("API Armamento asignación actualizada: {$weapon_assignment->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Asignación actualizada correctamente.',
                'data' => $weapon_assignment->fresh()->load(['weapon', 'personal']),
            ]);
        } catch (\Throwable $e) {
            Log::error('API Error al actualizar asignación: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al actualizar la asignación.',
            ], 500);
        }
    }

    public function destroy(WeaponAssignment $weapon_assignment)
    {
        try {
            $id = $weapon_assignment->id;
            $weapon_assignment->delete();

            Log::info("API Armamento asignación eliminada: {$id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Asignación eliminada correctamente.',
            ]);
        } catch (\Throwable $e) {
            Log::error('API Error al eliminar asignación: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al eliminar la asignación.',
            ], 500);
        }
    }
}

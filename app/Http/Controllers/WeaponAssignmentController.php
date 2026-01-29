<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\Personal;
use App\Models\WeaponAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WeaponAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // En tus rutas ya pusiste can:ver/crear/editar/eliminar armamento, así que aquí no duplico.
    }

    public function index()
    {
        $weapon_assignments = WeaponAssignment::query()
            ->with(['weapon', 'personal'])
            ->orderByDesc('fecha_asignacion')
            ->get();

        return view('armamento_asignaciones.index', compact('weapon_assignments'));
    }

    public function create()
    {
        $weapons = Weapon::query()
            ->orderBy('tipo')
            ->orderBy('matricula')
            ->get();

        $personals = Personal::query()
            ->orderBy('nombres')
            ->get();

        return view('armamento_asignaciones.create', compact('weapons', 'personals'));
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

        // Regla clave: no permitir 2 asignaciones activas del mismo arma
        $status = strtoupper(trim($validated['status']));
        $devolucion = $validated['fecha_devolucion'] ?? null;

        if ($status === 'ASIGNADA' && empty($devolucion)) {
            $existsActive = WeaponAssignment::query()
                ->where('weapon_id', $validated['weapon_id'])
                ->whereNull('fecha_devolucion')
                ->whereIn('status', ['ASIGNADA', 'ASIGNADO'])
                ->exists();

            if ($existsActive) {
                return back()->withErrors('Esa arma ya está asignada. Primero registra la devolución.')->withInput();
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

            Log::info("Armamento asignación creada: {$assignment->id} weapon_id={$assignment->weapon_id} personal_id={$assignment->personal_id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento_asignaciones.index')->with('success', 'Asignación registrada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear asignación: " . $e->getMessage());
            return back()->withErrors('Hubo un error al registrar la asignación.')->withInput();
        }
    }

    public function show(WeaponAssignment $weapon_assignment)
    {
        $weapon_assignment->load(['weapon', 'personal']);

        return view('armamento_asignaciones.show', compact('weapon_assignment'));
    }

    public function edit(WeaponAssignment $weapon_assignment)
    {
        $weapon_assignment->load(['weapon', 'personal']);

        $weapons = Weapon::query()->orderBy('tipo')->orderBy('matricula')->get();
        $personals = Personal::query()->orderBy('nombres')->get();

        return view('armamento_asignaciones.edit', compact('weapon_assignment', 'weapons', 'personals'));
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

        // Misma regla: si lo dejas ASIGNADA sin devolución, asegúrate que no exista otra activa (excluyendo esta)
        if ($status === 'ASIGNADA' && empty($devolucion)) {
            $existsActive = WeaponAssignment::query()
                ->where('weapon_id', $validated['weapon_id'])
                ->where('id', '!=', $weapon_assignment->id)
                ->whereNull('fecha_devolucion')
                ->whereIn('status', ['ASIGNADA', 'ASIGNADO'])
                ->exists();

            if ($existsActive) {
                return back()->withErrors('Esa arma ya está asignada en otro registro activo. Registra devolución o cambia status.')->withInput();
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

            Log::info("Armamento asignación actualizada: {$weapon_assignment->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento_asignaciones.show', $weapon_assignment->id)->with('success', 'Asignación actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar asignación: " . $e->getMessage());
            return back()->withErrors('Hubo un error al actualizar la asignación.')->withInput();
        }
    }

    public function destroy(WeaponAssignment $weapon_assignment)
    {
        try {
            $id = $weapon_assignment->id;

            $weapon_assignment->delete();

            Log::info("Armamento asignación eliminada: {$id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento_asignaciones.index')->with('success', 'Asignación eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar asignación: " . $e->getMessage());
            return back()->withErrors('Hubo un error al eliminar la asignación.');
        }
    }
}

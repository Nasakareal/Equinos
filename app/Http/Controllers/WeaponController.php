<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WeaponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // OJO: en tus rutas ya pones middleware('can:...'), aquí es opcional.
        // Lo dejo ligero para no duplicar ni mover tu lógica.
    }

    public function index()
    {
        $weapons = Weapon::query()
            ->orderBy('tipo')
            ->orderBy('matricula')
            ->get();

        return view('armamento.index', compact('weapons'));
    }

    public function create()
    {
        return view('armamento.create');
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

            Log::info("Armamento creado: {$weapon->id} {$weapon->matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento.index')->with('success', 'Arma creada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear arma: " . $e->getMessage());
            return back()->withErrors('Hubo un error al crear el arma.')->withInput();
        }
    }

    public function show(Weapon $weapon)
    {
        // Carga asignaciones solo para mostrar en detalle si quieres
        $weapon->load([
            'assignments' => function ($q) {
                $q->orderByDesc('fecha_asignacion');
            },
            'assignments.personal'
        ]);

        return view('armamento.show', compact('weapon'));
    }

    public function edit(Weapon $weapon)
    {
        return view('armamento.edit', compact('weapon'));
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

            Log::info("Armamento actualizado: {$weapon->id} {$weapon->matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento.show', $weapon->id)->with('success', 'Arma actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar arma: " . $e->getMessage());
            return back()->withErrors('Hubo un error al actualizar el arma.')->withInput();
        }
    }

    public function destroy(Weapon $weapon)
    {
        try {
            // Si existe asignación activa, no permitir eliminar
            $active = $weapon->assignments()
                ->whereNull('fecha_devolucion')
                ->whereIn('status', ['ASIGNADA', 'ASIGNADO'])
                ->exists();

            if ($active) {
                return back()->withErrors('No se puede eliminar: el arma está asignada actualmente.');
            }

            $matricula = $weapon->matricula;
            $id = $weapon->id;

            $weapon->delete();

            Log::info("Armamento eliminado: {$id} {$matricula} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('armamento.index')->with('success', 'Arma eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar arma: " . $e->getMessage());
            return back()->withErrors('Hubo un error al eliminar el arma.');
        }
    }
}

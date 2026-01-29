<?php

namespace App\Http\Controllers;

use App\Models\IncidenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class IncidenceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $incidence_types = IncidenceType::query()
            ->orderBy('nombre')
            ->get();

        return view('incidence_types.index', compact('incidence_types'));
    }

    public function create()
    {
        return view('incidence_types.create');
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
                'clave' => strtoupper(trim($validated['clave'])),
                'nombre' => strtoupper(trim($validated['nombre'])),
                'afecta_servicio' => $validated['afecta_servicio'] ?? 1,
                'color' => isset($validated['color']) ? trim($validated['color']) : null,
                'activo' => $validated['activo'] ?? 1,
            ]);

            Log::info("Tipo de incidencia creado: {$type->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidence_types.index')
                ->with('success', 'Tipo de incidencia creado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear tipo de incidencia: " . $e->getMessage());
            return back()->withErrors('Error al crear el tipo de incidencia.')->withInput();
        }
    }

    public function show(IncidenceType $incidence_type)
    {
        return view('incidence_types.show', compact('incidence_type'));
    }

    public function edit(IncidenceType $incidence_type)
    {
        return view('incidence_types.edit', compact('incidence_type'));
    }

    public function update(Request $request, IncidenceType $incidence_type)
    {
        $validated = $request->validate([
            'clave' => [
                'required', 'string', 'max:60',
                Rule::unique('incidence_types', 'clave')->ignore($incidence_type->id),
            ],
            'nombre' => [
                'required', 'string', 'max:120',
                Rule::unique('incidence_types', 'nombre')->ignore($incidence_type->id),
            ],
            'afecta_servicio' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:30'],
            'activo' => ['nullable', 'boolean'],
        ]);

        try {
            $incidence_type->update([
                'clave' => strtoupper(trim($validated['clave'])),
                'nombre' => strtoupper(trim($validated['nombre'])),
                'afecta_servicio' => $validated['afecta_servicio'] ?? 1,
                'color' => isset($validated['color']) ? trim($validated['color']) : null,
                'activo' => $validated['activo'] ?? 1,
            ]);

            Log::info("Tipo de incidencia actualizado: {$incidence_type->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidence_types.show', $incidence_type->id)
                ->with('success', 'Tipo de incidencia actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar tipo de incidencia: " . $e->getMessage());
            return back()->withErrors('Error al actualizar el tipo de incidencia.')->withInput();
        }
    }

    public function destroy(IncidenceType $incidence_type)
    {
        try {
            $incidence_type->delete();

            Log::info("Tipo de incidencia eliminado: {$incidence_type->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidence_types.index')
                ->with('success', 'Tipo de incidencia eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar tipo de incidencia: " . $e->getMessage());
            return back()->withErrors('Error al eliminar el tipo de incidencia.');
        }
    }
}

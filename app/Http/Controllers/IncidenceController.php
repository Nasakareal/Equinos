<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\IncidenceType;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IncidenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $incidencias = Incidence::query()
            ->with(['type', 'personal'])
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('created_at')
            ->get();

        return view('incidencias.index', compact('incidencias'));
    }

    public function create()
    {
        $incidence_types = IncidenceType::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $personals = Personal::query()
            ->orderBy('nombres')
            ->get();

        return view('incidencias.create', compact('incidence_types', 'personals'));
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
                'comentario' => isset($validated['comentario']) ? trim($validated['comentario']) : null,
                'registrado_por' => Auth::id(),
            ]);

            Log::info("Incidencia creada: {$incidence->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidencias.index')
                ->with('success', 'Incidencia registrada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear incidencia: " . $e->getMessage());
            return back()->withErrors('Error al registrar la incidencia.')->withInput();
        }
    }

    public function show(Incidence $incidence)
    {
        $incidence->load(['type', 'personal']);

        return view('incidencias.show', compact('incidence'));
    }

    public function edit(Incidence $incidence)
    {
        $incidence->load(['type', 'personal']);

        $incidence_types = IncidenceType::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $personals = Personal::query()
            ->orderBy('nombres')
            ->get();

        return view('incidencias.edit', compact('incidence', 'incidence_types', 'personals'));
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
                'comentario' => isset($validated['comentario']) ? trim($validated['comentario']) : null,
            ]);

            Log::info("Incidencia actualizada: {$incidence->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidencias.show', $incidence->id)
                ->with('success', 'Incidencia actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar incidencia: " . $e->getMessage());
            return back()->withErrors('Error al actualizar la incidencia.')->withInput();
        }
    }

    public function destroy(Incidence $incidence)
    {
        try {
            $id = $incidence->id;
            $incidence->delete();

            Log::info("Incidencia eliminada: {$id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('incidencias.index')
                ->with('success', 'Incidencia eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar incidencia: " . $e->getMessage());
            return back()->withErrors('Error al eliminar la incidencia.');
        }
    }
}

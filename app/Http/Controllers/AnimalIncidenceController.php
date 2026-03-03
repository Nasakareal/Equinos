<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalIncidence;
use App\Models\IncidenceType;
use Illuminate\Http\Request;

class AnimalIncidenceController extends Controller
{
    public function index(Animal $animal)
    {
        $incidences = $animal->incidences()
            ->with('incidenceType')
            ->orderByDesc('fecha')
            ->get();

        return view('animales.incidencias.index', compact('animal', 'incidences'));
    }

    public function create(Animal $animal)
    {
        $types = IncidenceType::orderBy('nombre')->get();

        return view('animales.incidencias.create', compact('animal', 'types'));
    }

    public function store(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'incidence_type_id' => 'nullable|exists:incidence_types,id',
            'gravedad' => 'required|in:BAJA,MEDIA,ALTA',
            'descripcion' => 'nullable|string'
        ]);

        $validated['animal_id'] = $animal->id;

        AnimalIncidence::create($validated);

        return redirect()->route('animales.incidencias.index', $animal)
            ->with('success', 'Incidencia registrada');
    }

    public function edit(Animal $animal, AnimalIncidence $incidence)
    {
        $types = IncidenceType::orderBy('nombre')->get();

        return view('animales.incidencias.edit', compact('animal', 'incidence', 'types'));
    }

    public function update(Request $request, Animal $animal, AnimalIncidence $incidence)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'incidence_type_id' => 'nullable|exists:incidence_types,id',
            'gravedad' => 'required|in:BAJA,MEDIA,ALTA',
            'descripcion' => 'nullable|string'
        ]);

        $incidence->update($validated);

        return redirect()->route('animales.incidencias.index', $animal)
            ->with('success', 'Incidencia actualizada');
    }

    public function destroy(Animal $animal, AnimalIncidence $incidence)
    {
        $incidence->delete();

        return redirect()->route('animales.incidencias.index', $animal)
            ->with('success', 'Incidencia eliminada');
    }
}

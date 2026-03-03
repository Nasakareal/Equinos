<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;

class AnimalMedicalRecordController extends Controller
{
    public function index(Animal $animal)
    {
        $records = $animal->medicalRecords()
            ->orderByDesc('fecha')
            ->get();

        return view('animales.medico.index', compact('animal', 'records'));
    }

    public function create(Animal $animal)
    {
        return view('animales.medico.create', compact('animal'));
    }

    public function store(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'veterinario' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric',
            'proxima_cita' => 'nullable|date'
        ]);

        $validated['animal_id'] = $animal->id;

        AnimalMedicalRecord::create($validated);

        return redirect()->route('animales.show', $animal->id)
            ->with('success', 'Registro médico agregado');
    }

    public function edit(Animal $animal, AnimalMedicalRecord $record)
    {
        $record->load('files');
        return view('animales.medico.edit', compact('animal', 'record'));
    }

    public function update(Request $request, Animal $animal, AnimalMedicalRecord $record)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'veterinario' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric',
            'proxima_cita' => 'nullable|date'
        ]);

        $record->update($validated);

        return redirect()->route('animales.show', $animal->id)
            ->with('success', 'Registro médico actualizado');
    }

    public function destroy(Animal $animal, AnimalMedicalRecord $record)
    {
        $record->delete();

        return redirect()->route('animales.show', $animal->id)
            ->with('success', 'Registro médico eliminado');
    }
}

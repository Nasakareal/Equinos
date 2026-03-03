<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('raza', 'like', '%' . $request->buscar . '%')
                  ->orWhere('especialidad', 'like', '%' . $request->buscar . '%');
            });
        }

        $animals = $query->orderBy('nombre')->paginate(20);

        return view('animales.index', compact('animals'));
    }

    public function create()
    {
        return view('animales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:EQUINO,CANINO',
            'nombre' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'procedencia' => 'nullable|string|max:255',
            'sexo' => 'nullable|in:MACHO,HEMBRA',
            'color' => 'nullable|string|max:255',
            'caracteristicas' => 'nullable|string',
            'marcaje' => 'nullable|string|max:255',
            'chip' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
            'estatus' => 'required|in:ACTIVO,BAJA,RESGUARDO',
            'observaciones' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'edad_texto' => 'nullable|string|max:255',
            'forraje_kg_diario' => 'nullable|numeric',
            'grano_kg_diario' => 'nullable|numeric',
        ]);

        Animal::create($validated);

        return redirect()->route('animales.index')
            ->with('success', 'Animal registrado correctamente');
    }

    public function show(Animal $animal)
    {
        $animal->load([
            'assignments.personal',
            'assignments.patrol',
            'medicalRecords',
            'incidences'
        ]);

        return view('animales.show', compact('animal'));
    }

    public function edit(Animal $animal)
    {
        return view('animales.edit', compact('animal'));
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:EQUINO,CANINO',
            'nombre' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'procedencia' => 'nullable|string|max:255',
            'sexo' => 'nullable|in:MACHO,HEMBRA',
            'color' => 'nullable|string|max:255',
            'caracteristicas' => 'nullable|string',
            'marcaje' => 'nullable|string|max:255',
            'chip' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
            'estatus' => 'required|in:ACTIVO,BAJA,RESGUARDO',
            'observaciones' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'edad_texto' => 'nullable|string|max:255',
            'forraje_kg_diario' => 'nullable|numeric',
            'grano_kg_diario' => 'nullable|numeric',
        ]);

        $animal->update($validated);

        return redirect()->route('animales.index')
            ->with('success', 'Animal actualizado correctamente');
    }

    public function destroy(Animal $animal)
    {
        $animal->delete();

        return redirect()->route('animales.index')
            ->with('success', 'Animal eliminado correctamente');
    }
}

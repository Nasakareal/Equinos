<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', (string)$request->query('tipo'));
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', (string)$request->query('estatus'));
        }

        if ($request->filled('buscar')) {
            $buscar = trim((string)$request->query('buscar'));
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('raza', 'like', '%' . $buscar . '%')
                  ->orWhere('especialidad', 'like', '%' . $buscar . '%');
            });
        }

        $perPage = (int)($request->query('per_page', 20));
        if ($perPage <= 0) $perPage = 20;
        if ($perPage > 100) $perPage = 100;

        $animals = $query->orderBy('nombre')->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $animals,
        ]);
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

        try {
            $animal = Animal::create($validated);

            return response()->json([
                'ok' => true,
                'message' => 'Animal registrado correctamente',
                'data' => $animal,
            ], 201);
        } catch (\Exception $e) {
            Log::error("API Error al crear animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al registrar el animal',
            ], 500);
        }
    }

    public function show(Animal $animal)
    {
        $animal->load([
            'assignments.personal',
            'assignments.patrol',
            'medicalRecords',
            'incidences',
        ]);

        return response()->json([
            'ok' => true,
            'data' => $animal,
        ]);
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

        try {
            $animal->update($validated);

            $animal->refresh();

            return response()->json([
                'ok' => true,
                'message' => 'Animal actualizado correctamente',
                'data' => $animal,
            ]);
        } catch (\Exception $e) {
            Log::error("API Error al actualizar animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el animal',
            ], 500);
        }
    }

    public function destroy(Animal $animal)
    {
        try {
            $animal->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Animal eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            Log::error("API Error al eliminar animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el animal',
            ], 500);
        }
    }
}

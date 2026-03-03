<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnimalMedicalRecordController extends Controller
{
    public function index(Animal $animal)
    {
        $records = $animal->medicalRecords()
            ->with('files')
            ->orderByDesc('fecha')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $records
        ]);
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

        try {

            $validated['animal_id'] = $animal->id;

            $record = AnimalMedicalRecord::create($validated);

            return response()->json([
                'ok' => true,
                'message' => 'Registro médico agregado',
                'data' => $record
            ], 201);

        } catch (\Exception $e) {

            Log::error("API Error al crear registro medico animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el registro médico'
            ], 500);
        }
    }

    public function show(Animal $animal, AnimalMedicalRecord $record)
    {
        $record->load('files');

        return response()->json([
            'ok' => true,
            'data' => $record
        ]);
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

        try {

            $record->update($validated);

            $record->refresh();

            return response()->json([
                'ok' => true,
                'message' => 'Registro médico actualizado',
                'data' => $record
            ]);

        } catch (\Exception $e) {

            Log::error("API Error al actualizar registro medico animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el registro médico'
            ], 500);
        }
    }

    public function destroy(Animal $animal, AnimalMedicalRecord $record)
    {
        try {

            $record->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Registro médico eliminado'
            ]);

        } catch (\Exception $e) {

            Log::error("API Error al eliminar registro medico animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el registro médico'
            ], 500);
        }
    }
}

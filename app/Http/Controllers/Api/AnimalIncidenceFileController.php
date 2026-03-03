<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalMedicalFile;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AnimalMedicalFileController extends Controller
{
    public function store(Request $request, Animal $animal, $record)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240'
        ]);

        $recordModel = AnimalMedicalRecord::query()
            ->where('id', $record)
            ->where('animal_id', $animal->id)
            ->firstOrFail();

        try {
            $path = $request->file('archivo')->store('animals/medical', 'public');

            $file = AnimalMedicalFile::create([
                'animal_medical_record_id' => $recordModel->id,
                'archivo' => $path,
                'tipo' => $request->file('archivo')->getClientOriginalExtension()
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Archivo agregado',
                'data' => $file
            ], 201);

        } catch (\Exception $e) {

            Log::error("API Error al subir archivo medico animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al subir el archivo'
            ], 500);
        }
    }

    public function destroy(AnimalMedicalFile $file)
    {
        try {

            if ($file->archivo && Storage::disk('public')->exists($file->archivo)) {
                Storage::disk('public')->delete($file->archivo);
            }

            $file->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Archivo eliminado'
            ]);

        } catch (\Exception $e) {

            Log::error("API Error al eliminar archivo medico animal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el archivo'
            ], 500);
        }
    }
}

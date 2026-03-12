<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalIncidence;
use App\Models\AnimalIncidenceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnimalIncidenceFileController extends Controller
{
    public function store(Request $request, Animal $animal, AnimalIncidence $incidence)
    {
        if ((int) $incidence->animal_id !== (int) $animal->id) {
            abort(404);
        }

        $request->validate([
            'archivo' => 'required|file|max:10240',
        ]);

        try {
            $path = $request->file('archivo')->store('animals/incidences', 'public');

            $file = AnimalIncidenceFile::create([
                'animal_incidence_id' => $incidence->id,
                'archivo' => $path,
                'tipo' => $request->file('archivo')->getClientOriginalExtension(),
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Archivo agregado.',
                'data' => $file,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('API Error al subir archivo de incidencia animal: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Error al subir el archivo.'], 500);
        }
    }

    public function destroy(AnimalIncidenceFile $file)
    {
        try {
            if ($file->archivo && Storage::disk('public')->exists($file->archivo)) {
                Storage::disk('public')->delete($file->archivo);
            }

            $file->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Archivo eliminado.',
            ]);
        } catch (\Throwable $e) {
            Log::error('API Error al eliminar archivo de incidencia animal: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Error al eliminar el archivo.'], 500);
        }
    }
}

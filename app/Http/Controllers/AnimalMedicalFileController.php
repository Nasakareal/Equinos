<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalMedicalFile;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalMedicalFileController extends Controller
{
    public function store(Request $request, Animal $animal, $record)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240'
        ]);

        $recordModel = AnimalMedicalRecord::where('id', $record)
            ->where('animal_id', $animal->id)
            ->firstOrFail();

        $path = $request->file('archivo')->store('animals/medical', 'public');

        AnimalMedicalFile::create([
            'animal_medical_record_id' => $recordModel->id,
            'archivo' => $path,
            'tipo' => $request->file('archivo')->getClientOriginalExtension()
        ]);

        return back()->with('success', 'Archivo agregado');
    }

    public function destroy(AnimalMedicalFile $file)
    {
        Storage::delete($file->archivo);
        $file->delete();

        return back()->with('success', 'Archivo eliminado');
    }
}

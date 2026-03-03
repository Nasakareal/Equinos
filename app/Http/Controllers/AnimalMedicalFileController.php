<?php

namespace App\Http\Controllers;

use App\Models\AnimalMedicalFile;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalMedicalFileController extends Controller
{
    public function store(Request $request, AnimalMedicalRecord $record)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240'
        ]);

        $path = $request->file('archivo')->store('animals/medical');

        AnimalMedicalFile::create([
            'animal_medical_record_id' => $record->id,
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

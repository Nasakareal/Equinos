<?php

namespace App\Http\Controllers;

use App\Models\AnimalIncidenceFile;
use App\Models\AnimalIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalIncidenceFileController extends Controller
{
    public function store(Request $request, AnimalIncidence $incidence)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240'
        ]);

        $path = $request->file('archivo')->store('animals/incidences');

        AnimalIncidenceFile::create([
            'animal_incidence_id' => $incidence->id,
            'archivo' => $path,
            'tipo' => $request->file('archivo')->getClientOriginalExtension()
        ]);

        return back()->with('success', 'Archivo agregado');
    }

    public function destroy(AnimalIncidenceFile $file)
    {
        Storage::delete($file->archivo);
        $file->delete();

        return back()->with('success', 'Archivo eliminado');
    }
}

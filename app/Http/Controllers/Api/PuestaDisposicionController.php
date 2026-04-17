<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\PuestaDisposicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PuestaDisposicionController extends Controller
{
    public function index(Request $request)
    {
        $query = PuestaDisposicion::query()
            ->with(['personal'])
            ->orderByDesc('anio')
            ->orderByDesc('created_at');

        if ($request->filled('anio')) {
            $query->where('anio', (int) $request->anio);
        }

        if ($request->filled('personal_id')) {
            $query->where('personal_id', (int) $request->personal_id);
        }

        if ($request->filled('buscar')) {
            $buscar = (string) $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('folio', 'like', '%' . $buscar . '%')
                    ->orWhere('observaciones', 'like', '%' . $buscar . '%');
            });
        }

        $perPage = (int) $request->get('per_page', 20);
        $perPage = $perPage > 0 ? min($perPage, 100) : 20;

        $puestas = $query->paginate($perPage);

        return response()->json($puestas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'anio' => 'required|integer|min:2000|max:2100',
            'hecho_id' => 'nullable|integer',
            'folio' => [
                'required',
                'string',
                'max:60',
                Rule::unique('puestas_disposicions', 'folio')->where(function ($q) use ($request) {
                    return $q->where('anio', (int) $request->input('anio'));
                }),
            ],
            'observaciones' => 'nullable|string',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ], [
            'folio.unique' => 'Ese folio ya existe en ese año.',
        ]);

        $userId = Auth::id();
        $anio = (int) $validated['anio'];

        $path = $request->file('archivo_pdf')->store("puestas_disposicion/{$anio}", 'public');

        $pd = PuestaDisposicion::create([
            'personal_id' => (int) $validated['personal_id'],
            'hecho_id' => $validated['hecho_id'] ?? null,
            'anio' => $anio,
            'folio' => trim((string) $validated['folio']),
            'archivo_pdf' => $path,
            'observaciones' => $validated['observaciones'] ?? null,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        $newPath = "puestas_disposicion/{$anio}/{$pd->id}.pdf";
        if ($pd->archivo_pdf !== $newPath) {
            Storage::disk('public')->move($pd->archivo_pdf, $newPath);
            $pd->update(['archivo_pdf' => $newPath]);
        }

        $pd->load(['personal']);

        return response()->json([
            'message' => 'Puesta a disposición registrada correctamente',
            'data' => $pd,
        ], 201);
    }

    public function show(PuestaDisposicion $puesta_disposicion)
    {
        $puesta_disposicion->load(['personal']);

        return response()->json([
            'data' => $puesta_disposicion,
        ]);
    }

    public function update(Request $request, PuestaDisposicion $puesta_disposicion)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'anio' => 'required|integer|min:2000|max:2100',
            'hecho_id' => 'nullable|integer',
            'folio' => [
                'required',
                'string',
                'max:60',
                Rule::unique('puestas_disposicions', 'folio')->where(function ($q) use ($request) {
                    return $q->where('anio', (int) $request->input('anio'));
                })->ignore($puesta_disposicion->id),
            ],
            'observaciones' => 'nullable|string',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'folio.unique' => 'Ese folio ya existe en ese año.',
        ]);

        $userId = Auth::id();
        $anio = (int) $validated['anio'];

        $puesta_disposicion->update([
            'personal_id' => (int) $validated['personal_id'],
            'hecho_id' => $validated['hecho_id'] ?? null,
            'anio' => $anio,
            'folio' => trim((string) $validated['folio']),
            'observaciones' => $validated['observaciones'] ?? null,
            'updated_by' => $userId,
        ]);

        if ($request->hasFile('archivo_pdf')) {
            if (!empty($puesta_disposicion->archivo_pdf) && Storage::disk('public')->exists($puesta_disposicion->archivo_pdf)) {
                Storage::disk('public')->delete($puesta_disposicion->archivo_pdf);
            }

            $newPath = "puestas_disposicion/{$anio}/{$puesta_disposicion->id}.pdf";
            $tmpPath = $request->file('archivo_pdf')->store("puestas_disposicion/{$anio}", 'public');

            if ($tmpPath !== $newPath) {
                Storage::disk('public')->move($tmpPath, $newPath);
                $puesta_disposicion->update(['archivo_pdf' => $newPath]);
            } else {
                $puesta_disposicion->update(['archivo_pdf' => $tmpPath]);
            }
        }

        $puesta_disposicion->load(['personal']);

        return response()->json([
            'message' => 'Puesta a disposición actualizada correctamente',
            'data' => $puesta_disposicion,
        ]);
    }

    public function destroy(PuestaDisposicion $puesta_disposicion)
    {
        if (!empty($puesta_disposicion->archivo_pdf) && Storage::disk('public')->exists($puesta_disposicion->archivo_pdf)) {
            Storage::disk('public')->delete($puesta_disposicion->archivo_pdf);
        }

        $puesta_disposicion->delete();

        return response()->json([
            'message' => 'Puesta a disposición eliminada correctamente',
        ]);
    }

    public function catalogos()
    {
        $personals = Personal::query()
            ->orderBy('nombres')
            ->get(['id', 'nombres', 'grado', 'cargo']);

        return response()->json([
            'personals' => $personals,
            'anio_actual' => now('America/Mexico_City')->year,
        ]);
    }
}

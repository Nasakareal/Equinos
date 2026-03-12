<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::query()->orderBy('id')->get();

        return response()->json([
            'ok' => true,
            'data' => $turnos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave' => 'required|string|max:20|unique:turnos,clave',
            'nombre' => 'required|string|max:100|unique:turnos,nombre',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        $turno = Turno::create($validated);

        return response()->json([
            'ok' => true,
            'message' => 'Turno creado correctamente.',
            'data' => $turno,
        ], 201);
    }

    public function show(Turno $turno)
    {
        return response()->json([
            'ok' => true,
            'data' => $turno,
        ]);
    }

    public function update(Request $request, Turno $turno)
    {
        $validated = $request->validate([
            'clave' => 'required|string|max:20|unique:turnos,clave,' . $turno->id,
            'nombre' => 'required|string|max:100|unique:turnos,nombre,' . $turno->id,
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        $turno->update($validated);

        return response()->json([
            'ok' => true,
            'message' => 'Turno actualizado correctamente.',
            'data' => $turno->fresh(),
        ]);
    }

    public function destroy(Turno $turno)
    {
        $turno->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Turno eliminado correctamente.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:ver personal')->only(['index', 'show']);
        $this->middleware('can:crear personal')->only(['create', 'store']);
        $this->middleware('can:editar personal')->only(['edit', 'update']);
        $this->middleware('can:eliminar personal')->only(['destroy']);
    }

    public function index()
    {
        $personals = Personal::query()
            ->with('user')
            ->orderBy('nombres')
            ->get();

        return view('personal.index', compact('personals'));
    }

    public function create()
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('personal.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',

            'no_empleado' => 'nullable|string|max:50',
            'cuip' => 'nullable|string|max:40',
            'grado' => 'nullable|string|max:60',
            'nombres' => 'required|string|max:255',
            'dependencia' => 'nullable|string|max:120',
            'crp' => 'nullable|string|max:60',
            'celular' => 'nullable|string|max:10',

            'cargo' => 'nullable|string|max:160',
            'es_responsable' => 'nullable|boolean',
            'area_patrullaje' => 'nullable|string|max:180',
            'observaciones' => 'nullable|string|max:1000',

            'activo' => 'nullable|boolean',
        ]);

        try {
            $personal = Personal::create([
                'user_id' => $validatedData['user_id'] ?? null,

                'no_empleado' => $validatedData['no_empleado'] ?? null,
                'cuip' => $validatedData['cuip'] ?? null,
                'grado' => $validatedData['grado'] ?? null,
                'nombres' => $validatedData['nombres'],
                'dependencia' => $validatedData['dependencia'] ?? null,
                'crp' => $validatedData['crp'] ?? null,
                'celular' => $validatedData['celular'] ?? null,

                'cargo' => $validatedData['cargo'] ?? null,
                'es_responsable' => (bool) ($validatedData['es_responsable'] ?? false),
                'area_patrullaje' => $validatedData['area_patrullaje'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,

                'activo' => (bool) ($validatedData['activo'] ?? true),
            ]);

            Log::info("Personal creado: {$personal->id} {$personal->nombres} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('personal.index')->with('success', 'Personal creado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear personal: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al crear el personal.')->withInput();
        }
    }

    public function show($id)
    {
        $personal = Personal::query()
            ->with('user')
            ->findOrFail($id);

        return view('personal.show', compact('personal'));
    }

    public function edit($id)
    {
        $personal = Personal::query()
            ->with('user')
            ->findOrFail($id);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('personal.edit', compact('personal', 'users'));
    }

    public function update(Request $request, $id)
    {
        $personal = Personal::query()->findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',

            'no_empleado' => 'nullable|string|max:50',
            'cuip' => 'nullable|string|max:40',
            'grado' => 'nullable|string|max:60',
            'nombres' => 'required|string|max:255',
            'dependencia' => 'nullable|string|max:120',
            'crp' => 'nullable|string|max:60',
            'celular' => 'nullable|string|max:10',

            'cargo' => 'nullable|string|max:160',
            'es_responsable' => 'nullable|boolean',
            'area_patrullaje' => 'nullable|string|max:180',
            'observaciones' => 'nullable|string|max:1000',

            'activo' => 'nullable|boolean',
        ]);

        try {
            $personal->update([
                'user_id' => $validatedData['user_id'] ?? null,

                'no_empleado' => $validatedData['no_empleado'] ?? null,
                'cuip' => $validatedData['cuip'] ?? null,
                'grado' => $validatedData['grado'] ?? null,
                'nombres' => $validatedData['nombres'],
                'dependencia' => $validatedData['dependencia'] ?? null,
                'crp' => $validatedData['crp'] ?? null,
                'celular' => $validatedData['celular'] ?? null,

                'cargo' => $validatedData['cargo'] ?? null,
                'es_responsable' => (bool) ($validatedData['es_responsable'] ?? false),
                'area_patrullaje' => $validatedData['area_patrullaje'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,

                'activo' => (bool) ($validatedData['activo'] ?? true),
            ]);

            Log::info("Personal actualizado: {$personal->id} {$personal->nombres} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('personal.index')->with('success', 'Personal actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar personal: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al actualizar el personal.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $personal = Personal::query()->findOrFail($id);

            $nombre = $personal->nombres;
            $idPersonal = $personal->id;

            $personal->delete();

            Log::info("Personal eliminado: {$idPersonal} {$nombre} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()->route('personal.index')->with('success', 'Personal eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar personal: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al eliminar el personal.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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

    /**
     * Normaliza un texto para comparar:
     * - trim
     * - colapsa espacios múltiples
     * - convierte a MAYÚSCULAS (para comparación case-insensitive)
     */
    private function normalizeText(?string $value): ?string
    {
        if ($value === null) return null;

        $value = trim($value);
        if ($value === '') return null;

        // colapsa espacios múltiples
        $value = preg_replace('/\s+/u', ' ', $value);

        // a mayúsculas (UTF-8)
        $value = mb_strtoupper($value, 'UTF-8');

        return $value;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',

            'no_empleado' => 'nullable|string|max:50',

            // ✅ CUIP no repetido (si viene)
            'cuip' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('personals', 'cuip')->where(function ($q) use ($request) {
                    // Evita que " " cuente como valor
                    $cuip = $this->normalizeText($request->input('cuip'));
                    if ($cuip === null) {
                        // si está vacío, no forzamos unique
                        $q->whereRaw('1=0');
                        return $q;
                    }
                    return $q;
                }),
            ],

            'grado' => 'nullable|string|max:60',

            // ✅ Nombre requerido, y validación anti-duplicado "humana" (case/espacios)
            'nombres' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $norm = $this->normalizeText($value);

                    $exists = Personal::query()
                        ->get(['id', 'nombres'])
                        ->contains(function ($p) use ($norm) {
                            return $this->normalizeText($p->nombres) === $norm;
                        });

                    if ($exists) {
                        $fail('Ese nombre ya está registrado.');
                    }
                }
            ],

            'dependencia' => 'nullable|string|max:120',
            'crp' => 'nullable|string|max:60',

            // ✅ Celular no repetido (si viene)
            'celular' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('personals', 'celular')->where(function ($q) use ($request) {
                    $cel = $this->normalizeText($request->input('celular'));
                    if ($cel === null) {
                        $q->whereRaw('1=0');
                        return $q;
                    }
                    return $q;
                }),
            ],

            'cargo' => 'nullable|string|max:160',
            'es_responsable' => 'nullable|boolean',
            'area_patrullaje' => 'nullable|string|max:180',
            'observaciones' => 'nullable|string|max:1000',

            'activo' => 'nullable|boolean',
        ], [
            'cuip.unique' => 'Ese CUIP ya está registrado.',
            'celular.unique' => 'Ese celular ya está registrado.',
        ]);

        // ✅ Normaliza CUIP/CELULAR/NOMBRES antes de guardar (para consistencia)
        $validatedData['cuip'] = $this->normalizeText($validatedData['cuip'] ?? null);
        $validatedData['celular'] = $this->normalizeText($validatedData['celular'] ?? null);
        $validatedData['nombres'] = $this->normalizeText($validatedData['nombres'] ?? null) ?? $validatedData['nombres'];

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

            // ✅ CUIP unique ignorando este registro
            'cuip' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('personals', 'cuip')->ignore($personal->id),
            ],

            'grado' => 'nullable|string|max:60',

            // ✅ Nombre no duplicado (case/espacios) ignorando este registro
            'nombres' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($personal) {
                    $norm = $this->normalizeText($value);

                    $exists = Personal::query()
                        ->where('id', '!=', $personal->id)
                        ->get(['id', 'nombres'])
                        ->contains(function ($p) use ($norm) {
                            return $this->normalizeText($p->nombres) === $norm;
                        });

                    if ($exists) {
                        $fail('Ese nombre ya está registrado.');
                    }
                }
            ],

            'dependencia' => 'nullable|string|max:120',
            'crp' => 'nullable|string|max:60',

            // ✅ Celular unique ignorando este registro
            'celular' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('personals', 'celular')->ignore($personal->id),
            ],

            'cargo' => 'nullable|string|max:160',
            'es_responsable' => 'nullable|boolean',
            'area_patrullaje' => 'nullable|string|max:180',
            'observaciones' => 'nullable|string|max:1000',

            'activo' => 'nullable|boolean',
        ], [
            'cuip.unique' => 'Ese CUIP ya está registrado.',
            'celular.unique' => 'Ese celular ya está registrado.',
        ]);

        // ✅ Normaliza CUIP/CELULAR/NOMBRES antes de guardar (para consistencia)
        $validatedData['cuip'] = $this->normalizeText($validatedData['cuip'] ?? null);
        $validatedData['celular'] = $this->normalizeText($validatedData['celular'] ?? null);
        $validatedData['nombres'] = $this->normalizeText($validatedData['nombres'] ?? null) ?? $validatedData['nombres'];

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

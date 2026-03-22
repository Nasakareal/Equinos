<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Animal;
use App\Models\Personal;
use App\Models\Patrol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:ver servicios')->only(['index', 'show']);
        $this->middleware('can:crear servicios')->only(['create', 'store']);
        $this->middleware('can:editar servicios')->only(['edit', 'update']);
        $this->middleware('can:eliminar servicios')->only(['destroy']);
    }

    private function normalizeText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $value = preg_replace('/\s+/u', ' ', $value);
        $value = mb_strtoupper($value, 'UTF-8');

        return $value;
    }

    public function index()
    {
        $servicios = Servicio::query()
            ->with('creador')
            ->with('personal')
            ->with('canino')
            ->with('equino')
            ->with('patrulla')
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $personales = Personal::query()
            ->where('activo', 1)
            ->orderBy('nombres')
            ->get();

        $caninos = Animal::query()
            ->where('tipo', 'CANINO')
            ->where('estatus', 'ACTIVO')
            ->orderBy('nombre')
            ->get();

        $equinos = Animal::query()
            ->where('tipo', 'EQUINO')
            ->where('estatus', 'ACTIVO')
            ->orderBy('nombre')
            ->get();

        $patrullas = Patrol::query()
            ->orderBy('id')
            ->get();

        return view('servicios.create', compact('personales', 'caninos', 'equinos', 'patrullas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'categoria_registro' => ['required', 'string', Rule::in(['SERVICIO', 'APOYO', 'MEMORANDUM'])],
            'tipo_servicio' => ['required', 'string'],
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'cumplio' => 'nullable|boolean',
            'seguridad' => 'nullable|boolean',
            'barrido_seguridad' => 'nullable|boolean',
            'tipo_busqueda' => 'nullable|string',
            'desfiles' => 'nullable|boolean',
            'proximidad_social' => 'nullable|boolean',
            'actos_civicos' => 'nullable|boolean',
            'personal_id' => 'nullable|exists:personals,id',
            'canino_id' => 'nullable|exists:animals,id',
            'equino_id' => 'nullable|exists:animals,id',
            'patrulla_id' => 'nullable',
            'asunto' => 'nullable|string|max:255',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $validatedData['categoria_registro'] = $this->normalizeText($validatedData['categoria_registro'] ?? null);
        $validatedData['tipo_servicio'] = $this->normalizeText($validatedData['tipo_servicio'] ?? null);
        $validatedData['tipo_busqueda'] = $this->normalizeText($validatedData['tipo_busqueda'] ?? null);
        $validatedData['created_by'] = Auth::id();
        $validatedData['cumplio'] = (bool) ($validatedData['cumplio'] ?? false);
        $validatedData['seguridad'] = (bool) ($validatedData['seguridad'] ?? false);
        $validatedData['barrido_seguridad'] = (bool) ($validatedData['barrido_seguridad'] ?? false);
        $validatedData['desfiles'] = (bool) ($validatedData['desfiles'] ?? false);
        $validatedData['proximidad_social'] = (bool) ($validatedData['proximidad_social'] ?? false);
        $validatedData['actos_civicos'] = (bool) ($validatedData['actos_civicos'] ?? false);

        try {
            $servicio = Servicio::create($validatedData);

            Log::info('Servicio creado: ' . $servicio->id . ' por usuario ' . (Auth::id() ?? 'N/A'));

            return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear servicio: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors('Hubo un error al crear el servicio.')
                ->withInput();
        }
    }

    public function show($id)
    {
        $servicio = Servicio::query()
            ->with('creador')
            ->with('personal')
            ->with('canino')
            ->with('equino')
            ->with('patrulla')
            ->findOrFail($id);

        return view('servicios.show', compact('servicio'));
    }

    public function edit($id)
    {
        $servicio = Servicio::query()
            ->with('creador')
            ->with('personal')
            ->with('canino')
            ->with('equino')
            ->with('patrulla')
            ->findOrFail($id);

        $personales = Personal::query()
            ->where('activo', 1)
            ->orderBy('nombres')
            ->get();

        $caninos = Animal::query()
            ->where('tipo', 'CANINO')
            ->where('estatus', 'ACTIVO')
            ->orderBy('nombre')
            ->get();

        $equinos = Animal::query()
            ->where('tipo', 'EQUINO')
            ->where('estatus', 'ACTIVO')
            ->orderBy('nombre')
            ->get();

        $patrullas = Patrol::query()
            ->orderBy('id')
            ->get();

        return view('servicios.edit', compact('servicio', 'personales', 'caninos', 'equinos', 'patrullas'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::query()->findOrFail($id);

        $validatedData = $request->validate([
            'categoria_registro' => ['required', 'string', Rule::in(['SERVICIO', 'APOYO', 'MEMORANDUM'])],
            'tipo_servicio' => ['required', 'string'],
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'cumplio' => 'nullable|boolean',
            'seguridad' => 'nullable|boolean',
            'barrido_seguridad' => 'nullable|boolean',
            'tipo_busqueda' => 'nullable|string',
            'desfiles' => 'nullable|boolean',
            'proximidad_social' => 'nullable|boolean',
            'actos_civicos' => 'nullable|boolean',
            'personal_id' => 'nullable|exists:personals,id',
            'canino_id' => 'nullable|exists:animals,id',
            'equino_id' => 'nullable|exists:animals,id',
            'patrulla_id' => 'nullable',
            'asunto' => 'nullable|string|max:255',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $validatedData['categoria_registro'] = $this->normalizeText($validatedData['categoria_registro'] ?? null);
        $validatedData['tipo_servicio'] = $this->normalizeText($validatedData['tipo_servicio'] ?? null);
        $validatedData['tipo_busqueda'] = $this->normalizeText($validatedData['tipo_busqueda'] ?? null);
        $validatedData['cumplio'] = (bool) ($validatedData['cumplio'] ?? false);
        $validatedData['seguridad'] = (bool) ($validatedData['seguridad'] ?? false);
        $validatedData['barrido_seguridad'] = (bool) ($validatedData['barrido_seguridad'] ?? false);
        $validatedData['desfiles'] = (bool) ($validatedData['desfiles'] ?? false);
        $validatedData['proximidad_social'] = (bool) ($validatedData['proximidad_social'] ?? false);
        $validatedData['actos_civicos'] = (bool) ($validatedData['actos_civicos'] ?? false);

        try {
            $servicio->update($validatedData);

            Log::info('Servicio actualizado: ' . $servicio->id . ' por usuario ' . (Auth::id() ?? 'N/A'));

            return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar servicio: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors('Hubo un error al actualizar el servicio.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $servicio = Servicio::query()->findOrFail($id);

            $idServicio = $servicio->id;

            $servicio->delete();

            Log::info('Servicio eliminado: ' . $idServicio . ' por usuario ' . (Auth::id() ?? 'N/A'));

            return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar servicio: ' . $e->getMessage());

            return redirect()->back()->withErrors('Hubo un error al eliminar servicio.');
        }
    }
}

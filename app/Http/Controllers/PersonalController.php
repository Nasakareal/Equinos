<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use App\Models\Turno;
use App\Models\ServiceSchedule;
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

    private function normalizeText(?string $value): ?string
    {
        if ($value === null) return null;

        $value = trim($value);
        if ($value === '') return null;

        $value = preg_replace('/\s+/u', ' ', $value);
        $value = mb_strtoupper($value, 'UTF-8');

        return $value;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'no_empleado' => 'nullable|string|max:50',

            'cuip' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('personals', 'cuip')->where(function ($q) use ($request) {
                    $cuip = $this->normalizeText($request->input('cuip'));
                    if ($cuip === null) {
                        $q->whereRaw('1=0');
                        return $q;
                    }
                    return $q;
                }),
            ],

            'grado' => 'nullable|string|max:60',

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
        // Cargamos también el servicio activo para mostrar su turno en show si quieres
        $personal = Personal::query()
            ->with('user')
            ->with(['servicios' => function ($q) {
                $q->where('activo', 1)->latest('id');
            }])
            ->findOrFail($id);

        return view('personal.show', compact('personal'));
    }

    public function edit($id)
    {
        $personal = Personal::query()
            ->with('user')
            ->with(['servicios' => function ($q) {
                $q->where('activo', 1)->latest('id');
            }])
            ->findOrFail($id);

        $users = User::query()->orderBy('name')->get();

        $turnos = Turno::query()->orderBy('id')->get();

        // servicio activo (si existe)
        $servicioActivo = $personal->servicios->first();

        return view('personal.edit', compact('personal', 'users', 'turnos', 'servicioActivo'));
    }

    public function update(Request $request, $id)
    {
        $personal = Personal::query()->findOrFail($id);

        $validatedData = $request->validate([
            // ===== personal =====
            'user_id' => 'nullable|exists:users,id',
            'no_empleado' => 'nullable|string|max:50',

            'cuip' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('personals', 'cuip')->ignore($personal->id),
            ],

            'grado' => 'nullable|string|max:60',

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

            // ===== servicio/turno =====
            // si no mandas estos campos, no tocamos el turno
            'turno_id' => 'nullable|exists:turnos,id',
            'servicio_activo' => 'nullable|boolean',
            'tipo' => 'nullable|string|max:20',
            'fecha_inicio_ciclo' => 'nullable|date',
            'horas_trabajo' => 'nullable|integer|min:1|max:168',
            'horas_descanso' => 'nullable|integer|min:0|max:168',
            'servicio_observaciones' => 'nullable|string|max:1000',
        ], [
            'cuip.unique' => 'Ese CUIP ya está registrado.',
            'celular.unique' => 'Ese celular ya está registrado.',
        ]);

        $validatedData['cuip'] = $this->normalizeText($validatedData['cuip'] ?? null);
        $validatedData['celular'] = $this->normalizeText($validatedData['celular'] ?? null);
        $validatedData['nombres'] = $this->normalizeText($validatedData['nombres'] ?? null) ?? $validatedData['nombres'];

        try {
            // 1) actualiza personal
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

            // 2) turno / service_schedule (solo si viene algo de turno en el request)
            $tocoServicio = $request->hasAny([
                'turno_id',
                'servicio_activo',
                'tipo',
                'fecha_inicio_ciclo',
                'horas_trabajo',
                'horas_descanso',
                'servicio_observaciones',
            ]);

            if ($tocoServicio) {
                $servicio_activo = (bool) ($validatedData['servicio_activo'] ?? true);

                // buscamos el registro activo actual
                $servicioActual = ServiceSchedule::query()
                    ->where('personal_id', $personal->id)
                    ->where('activo', 1)
                    ->latest('id')
                    ->first();

                if (!$servicio_activo) {
                    // desactivar servicio actual si existe
                    if ($servicioActual) {
                        $servicioActual->update([
                            'activo' => 0,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // si activas servicio, necesitamos fecha_inicio_ciclo (porque en tu tabla es NOT NULL)
                    $fecha_inicio_ciclo = $validatedData['fecha_inicio_ciclo'] ?? ($servicioActual->fecha_inicio_ciclo ?? now()->toDateString());

                    // defaults coherentes
                    $tipo = $validatedData['tipo'] ?? ($servicioActual->tipo ?? 'CICLICO');
                    $horas_trabajo = (int) ($validatedData['horas_trabajo'] ?? ($servicioActual->horas_trabajo ?? 24));
                    $horas_descanso = (int) ($validatedData['horas_descanso'] ?? ($servicioActual->horas_descanso ?? 24));

                    // si no mandan turno_id, no lo pisamos; si no existe servicio, sí exigimos turno_id para crear
                    $turno_id = $validatedData['turno_id'] ?? ($servicioActual->turno_id ?? null);

                    if (!$servicioActual && $turno_id === null) {
                        return redirect()->back()
                            ->withErrors('Selecciona un turno para activar el servicio.')
                            ->withInput();
                    }

                    // si ya había uno activo y cambias turno/ciclo: lo actualizamos (no creamos otro)
                    if ($servicioActual) {
                        $servicioActual->update([
                            'turno_id' => $turno_id,
                            'tipo' => $tipo,
                            'fecha_inicio_ciclo' => $fecha_inicio_ciclo,
                            'horas_trabajo' => $horas_trabajo,
                            'horas_descanso' => $horas_descanso,
                            'activo' => 1,
                            'observaciones' => $validatedData['servicio_observaciones'] ?? $servicioActual->observaciones,
                        ]);
                    } else {
                        ServiceSchedule::create([
                            'personal_id' => $personal->id,
                            'turno_id' => $turno_id,
                            'tipo' => $tipo,
                            'fecha_inicio_ciclo' => $fecha_inicio_ciclo,
                            'horas_trabajo' => $horas_trabajo,
                            'horas_descanso' => $horas_descanso,
                            'activo' => 1,
                            'observaciones' => $validatedData['servicio_observaciones'] ?? null,
                        ]);
                    }
                }
            }

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

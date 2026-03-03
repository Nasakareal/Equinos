<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Area;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PersonalController extends Controller
{
    private function normalizeText(?string $value): ?string
    {
        if ($value === null) return null;

        $value = trim($value);
        if ($value === '') return null;

        $value = preg_replace('/\s+/u', ' ', $value);
        $value = mb_strtoupper($value, 'UTF-8');

        return $value;
    }

    public function index(Request $request)
    {
        $q = Personal::query()
            ->with(['user:id,name,email', 'area', 'turno'])
            ->orderBy('nombres');

        if ($request->filled('dependencia')) {
            $q->where('dependencia', $this->normalizeText($request->query('dependencia')));
        }

        if ($request->filled('activo')) {
            $q->where('activo', (int)$request->query('activo') ? 1 : 0);
        }

        if ($request->filled('turno_id')) {
            $q->where('turno_id', (int)$request->query('turno_id'));
        }

        if ($request->filled('search')) {
            $s = $this->normalizeText((string)$request->query('search'));
            $q->where(function ($qq) use ($s) {
                $qq->whereRaw('UPPER(nombres) LIKE ?', ['%' . $s . '%'])
                   ->orWhereRaw('UPPER(cuip) LIKE ?', ['%' . $s . '%'])
                   ->orWhereRaw('UPPER(cargo) LIKE ?', ['%' . $s . '%']);
            });
        }

        $personals = $q->get();

        return response()->json([
            'ok' => true,
            'data' => $personals,
        ]);
    }

    public function show(Personal $personal)
    {
        $personal->load([
            'user:id,name,email',
            'area',
            'turno',
            'servicios' => function ($q) {
                $q->where('activo', 1)->latest('id');
            },
            'asignacionesArmamento' => function ($q) {
                $q->with('weapon')
                  ->orderByDesc('fecha_asignacion')
                  ->orderByDesc('id');
            },
        ]);

        $horario = \App\Models\PersonalHorario::query()
            ->where('personal_id', $personal->id)
            ->where('activo', 1)
            ->with(['detalles' => function ($q) {
                $q->orderBy('dia_semana')->orderBy('hora_entrada');
            }])
            ->first();

        if (!$horario) {
            $horario = \App\Models\PersonalHorario::create([
                'personal_id' => $personal->id,
                'activo' => 1,
                'nombre' => 'HORARIO MIXTO',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
            ]);

            $horario->load(['detalles' => function ($q) {
                $q->orderBy('dia_semana')->orderBy('hora_entrada');
            }]);
        }

        $armasActivas = $personal->asignacionesArmamento
            ->filter(function ($a) {
                return $a->status === 'ASIGNADA' && $a->fecha_devolucion === null;
            })
            ->values();

        return response()->json([
            'ok' => true,
            'data' => [
                'personal' => $personal,
                'armas_activas' => $armasActivas,
                'historial_armamento' => $personal->asignacionesArmamento,
                'horario' => $horario,
            ],
        ]);
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
            'area_id' => 'nullable|exists:areas,id',
            'turno_id' => 'nullable|exists:turnos,id',
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
            'siempre_visible' => 'nullable|boolean',
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
        $validatedData['dependencia'] = $this->normalizeText($validatedData['dependencia'] ?? null);
        $validatedData['grado'] = $this->normalizeText($validatedData['grado'] ?? null);
        $validatedData['cargo'] = $this->normalizeText($validatedData['cargo'] ?? null);
        $validatedData['crp'] = $this->normalizeText($validatedData['crp'] ?? null);

        try {
            $personal = Personal::create([
                'user_id' => $validatedData['user_id'] ?? null,
                'no_empleado' => $validatedData['no_empleado'] ?? null,
                'cuip' => $validatedData['cuip'] ?? null,
                'grado' => $validatedData['grado'] ?? null,
                'nombres' => $validatedData['nombres'],
                'dependencia' => $validatedData['dependencia'] ?? null,
                'area_id' => $validatedData['area_id'] ?? null,
                'turno_id' => $validatedData['turno_id'] ?? null,
                'crp' => $validatedData['crp'] ?? null,
                'celular' => $validatedData['celular'] ?? null,
                'cargo' => $validatedData['cargo'] ?? null,
                'es_responsable' => (bool) ($validatedData['es_responsable'] ?? false),
                'siempre_visible' => (bool) ($validatedData['siempre_visible'] ?? false),
                'area_patrullaje' => $validatedData['area_patrullaje'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,
                'activo' => (bool) ($validatedData['activo'] ?? true),
            ]);

            Log::info("API Personal creado: {$personal->id} {$personal->nombres} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Personal creado correctamente.',
                'data' => $personal,
            ], 201);
        } catch (\Exception $e) {
            Log::error("API Error al crear personal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al crear el personal.',
            ], 500);
        }
    }

    public function update(Request $request, Personal $personal)
    {
        $validatedData = $request->validate([
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
            'area_id' => 'nullable|exists:areas,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'crp' => 'nullable|string|max:60',
            'celular' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('personals', 'celular')->ignore($personal->id),
            ],
            'cargo' => 'nullable|string|max:160',
            'es_responsable' => 'nullable|boolean',
            'siempre_visible' => 'nullable|boolean',
            'area_patrullaje' => 'nullable|string|max:180',
            'observaciones' => 'nullable|string|max:1000',
            'activo' => 'nullable|boolean',
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
        $validatedData['dependencia'] = $this->normalizeText($validatedData['dependencia'] ?? null);
        $validatedData['grado'] = $this->normalizeText($validatedData['grado'] ?? null);
        $validatedData['cargo'] = $this->normalizeText($validatedData['cargo'] ?? null);
        $validatedData['crp'] = $this->normalizeText($validatedData['crp'] ?? null);

        try {
            $personal->update([
                'user_id' => $validatedData['user_id'] ?? null,
                'no_empleado' => $validatedData['no_empleado'] ?? null,
                'cuip' => $validatedData['cuip'] ?? null,
                'grado' => $validatedData['grado'] ?? null,
                'nombres' => $validatedData['nombres'],
                'dependencia' => $validatedData['dependencia'] ?? null,
                'area_id' => $validatedData['area_id'] ?? null,
                'turno_id' => $validatedData['turno_id'] ?? null,
                'crp' => $validatedData['crp'] ?? null,
                'celular' => $validatedData['celular'] ?? null,
                'cargo' => $validatedData['cargo'] ?? null,
                'es_responsable' => (bool) ($validatedData['es_responsable'] ?? false),
                'siempre_visible' => (bool) ($validatedData['siempre_visible'] ?? false),
                'area_patrullaje' => $validatedData['area_patrullaje'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,
                'activo' => (bool) ($validatedData['activo'] ?? true),
            ]);

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

                $servicioActual = ServiceSchedule::query()
                    ->where('personal_id', $personal->id)
                    ->where('activo', 1)
                    ->latest('id')
                    ->first();

                if (!$servicio_activo) {
                    if ($servicioActual) {
                        $servicioActual->update([
                            'activo' => 0,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    $fecha_inicio_ciclo = $validatedData['fecha_inicio_ciclo'] ?? ($servicioActual->fecha_inicio_ciclo ?? now()->toDateString());
                    $tipo = $validatedData['tipo'] ?? ($servicioActual->tipo ?? 'CICLICO');
                    $horas_trabajo = (int) ($validatedData['horas_trabajo'] ?? ($servicioActual->horas_trabajo ?? 24));
                    $horas_descanso = (int) ($validatedData['horas_descanso'] ?? ($servicioActual->horas_descanso ?? 24));

                    if ($servicioActual) {
                        $servicioActual->update([
                            'turno_id' => $personal->turno_id,
                            'tipo' => $tipo,
                            'fecha_inicio_ciclo' => $fecha_inicio_ciclo,
                            'horas_trabajo' => $horas_trabajo,
                            'horas_descanso' => $horas_descanso,
                            'activo' => 1,
                            'observaciones' => $validatedData['servicio_observaciones'] ?? $servicioActual->observaciones,
                        ]);
                    } else {
                        if ($personal->turno_id === null) {
                            return response()->json([
                                'ok' => false,
                                'message' => 'Selecciona un turno para activar el servicio.',
                            ], 422);
                        }

                        ServiceSchedule::create([
                            'personal_id' => $personal->id,
                            'turno_id' => $personal->turno_id,
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

            Log::info("API Personal actualizado: {$personal->id} {$personal->nombres} por usuario " . (Auth::id() ?? 'N/A'));

            $personal->refresh();

            return response()->json([
                'ok' => true,
                'message' => 'Personal actualizado correctamente.',
                'data' => $personal,
            ]);
        } catch (\Exception $e) {
            Log::error("API Error al actualizar personal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al actualizar el personal.',
            ], 500);
        }
    }

    public function destroy(Personal $personal)
    {
        try {
            $id = $personal->id;
            $nombre = $personal->nombres;

            $personal->delete();

            Log::info("API Personal eliminado: {$id} {$nombre} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Personal eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error("API Error al eliminar personal: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al eliminar personal.',
            ], 500);
        }
    }

    public function catalogos(Request $request)
    {
        $areas = Area::query()->where('activo', 1)->orderBy('nombre')->get();
        $turnos = Turno::query()->where('activo', 1)->orderBy('id')->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'areas' => $areas,
                'turnos' => $turnos,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:ver servicios')->only(['index', 'show']);
        $this->middleware('can:crear servicios')->only(['store']);
        $this->middleware('can:editar servicios')->only(['update']);
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

        return mb_strtoupper(preg_replace('/\s+/u', ' ', $value), 'UTF-8');
    }

    private function validationRules(): array
    {
        return [
            'categoria_registro' => ['required', 'string', Rule::in(['SERVICIO', 'APOYO', 'MEMORANDUM'])],
            'tipo_servicio' => ['required', 'string', 'max:255'],
            'folio_referencia' => ['nullable', 'string', 'max:255'],

            'estatus_servicio' => ['nullable', 'string', 'max:255'],
            'unidad_clave' => ['nullable', 'string', 'max:255'],
            'crp' => ['nullable', 'string', 'max:255'],
            'objetivo_servicio' => ['nullable', 'string', 'max:255'],
            'folio_operativo' => ['nullable', 'string', 'max:255'],

            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i'],

            'cumplio' => ['nullable', 'boolean'],
            'seguridad' => ['nullable', 'boolean'],
            'barrido_seguridad' => ['nullable', 'boolean'],
            'desfiles' => ['nullable', 'boolean'],
            'proximidad_social' => ['nullable', 'boolean'],
            'actos_civicos' => ['nullable', 'boolean'],

            'tipo_busqueda' => ['nullable', 'string', 'max:255'],
            'asunto' => ['nullable', 'string', 'max:255'],
            'municipio' => ['nullable', 'string', 'max:255'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'acciones_realizadas' => ['nullable', 'string'],
            'resultados' => ['nullable', 'string'],
            'conclusion_operativa' => ['nullable', 'string'],
            'comandante_responsable' => ['nullable', 'string', 'max:255'],
            'cargo_responsable' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],

            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],

            'personal_id' => ['nullable', 'exists:personals,id'],
            'canino_id' => ['nullable', 'exists:animals,id'],
            'equino_id' => ['nullable', 'exists:animals,id'],
            'patrulla_id' => ['nullable', 'exists:patrols,id'],

            'archivo' => ['nullable', 'string'],
            'archivo_nombre_original' => ['nullable', 'string', 'max:255'],
            'archivo_mime' => ['nullable', 'string', 'max:255'],
            'archivo_size' => ['nullable', 'integer'],

            'estado_fuerza.elementos' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.unidades' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.remolques' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.equinos' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.caninos' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.medicos_veterinarios' => ['nullable', 'integer', 'min:0'],
            'estado_fuerza.crp' => ['nullable', 'string', 'max:255'],
            'estado_fuerza.observaciones' => ['nullable', 'string'],

            'participantes' => ['nullable', 'array'],
            'participantes.*.institucion' => ['nullable', 'string', 'max:255'],
            'participantes.*.responsable' => ['nullable', 'string', 'max:255'],
            'participantes.*.elementos' => ['nullable', 'integer', 'min:0'],
            'participantes.*.vehiculos' => ['nullable', 'integer', 'min:0'],
            'participantes.*.unidad_identificador' => ['nullable', 'string', 'max:255'],
            'participantes.*.descripcion' => ['nullable', 'string'],

            'coordenadas' => ['nullable', 'array'],
            'coordenadas.*.lat' => ['nullable', 'numeric', 'between:-90,90'],
            'coordenadas.*.lng' => ['nullable', 'numeric', 'between:-180,180'],
            'coordenadas.*.descripcion' => ['nullable', 'string', 'max:255'],
            'coordenadas.*.orden' => ['nullable', 'integer', 'min:1'],

            'recursos' => ['nullable', 'array'],
            'recursos.*.tipo_recurso' => ['nullable', 'string', 'max:255'],
            'recursos.*.descripcion' => ['nullable', 'string', 'max:255'],
            'recursos.*.cantidad' => ['nullable', 'integer', 'min:1'],
        ];
    }

    private function normalizeValidatedData(array $validatedData): array
    {
        $validatedData['categoria_registro'] = $this->normalizeText($validatedData['categoria_registro'] ?? null);
        $validatedData['tipo_servicio'] = $this->normalizeText($validatedData['tipo_servicio'] ?? null);
        $validatedData['folio_referencia'] = $this->normalizeText($validatedData['folio_referencia'] ?? null);

        $validatedData['estatus_servicio'] = $this->normalizeText($validatedData['estatus_servicio'] ?? null);
        $validatedData['unidad_clave'] = $this->normalizeText($validatedData['unidad_clave'] ?? null);
        $validatedData['crp'] = $this->normalizeText($validatedData['crp'] ?? null);
        $validatedData['objetivo_servicio'] = $this->normalizeText($validatedData['objetivo_servicio'] ?? null);
        $validatedData['folio_operativo'] = $this->normalizeText($validatedData['folio_operativo'] ?? null);

        $validatedData['tipo_busqueda'] = $this->normalizeText($validatedData['tipo_busqueda'] ?? null);
        $validatedData['asunto'] = $this->normalizeText($validatedData['asunto'] ?? null);
        $validatedData['municipio'] = $this->normalizeText($validatedData['municipio'] ?? null);
        $validatedData['lugar'] = $this->normalizeText($validatedData['lugar'] ?? null);
        $validatedData['comandante_responsable'] = $this->normalizeText($validatedData['comandante_responsable'] ?? null);
        $validatedData['cargo_responsable'] = $this->normalizeText($validatedData['cargo_responsable'] ?? null);

        $validatedData['cumplio'] = (bool) ($validatedData['cumplio'] ?? false);
        $validatedData['seguridad'] = (bool) ($validatedData['seguridad'] ?? false);
        $validatedData['barrido_seguridad'] = (bool) ($validatedData['barrido_seguridad'] ?? false);
        $validatedData['desfiles'] = (bool) ($validatedData['desfiles'] ?? false);
        $validatedData['proximidad_social'] = (bool) ($validatedData['proximidad_social'] ?? false);
        $validatedData['actos_civicos'] = (bool) ($validatedData['actos_civicos'] ?? false);

        return $validatedData;
    }

    private function syncServicioDetalles(Servicio $servicio, array $validatedData): void
    {
        $estadoFuerza = $validatedData['estado_fuerza'] ?? [];

        $tieneEstadoFuerza =
            !empty($estadoFuerza['elementos']) ||
            !empty($estadoFuerza['unidades']) ||
            !empty($estadoFuerza['remolques']) ||
            !empty($estadoFuerza['equinos']) ||
            !empty($estadoFuerza['caninos']) ||
            !empty($estadoFuerza['medicos_veterinarios']) ||
            !empty($estadoFuerza['crp']) ||
            !empty($estadoFuerza['observaciones']);

        if ($tieneEstadoFuerza) {
            $servicio->estadoFuerza()->updateOrCreate(
                ['servicio_id' => $servicio->id],
                [
                    'elementos' => $estadoFuerza['elementos'] ?? null,
                    'unidades' => $estadoFuerza['unidades'] ?? null,
                    'remolques' => $estadoFuerza['remolques'] ?? null,
                    'equinos' => $estadoFuerza['equinos'] ?? null,
                    'caninos' => $estadoFuerza['caninos'] ?? null,
                    'medicos_veterinarios' => $estadoFuerza['medicos_veterinarios'] ?? null,
                    'crp' => $this->normalizeText($estadoFuerza['crp'] ?? null),
                    'observaciones' => $estadoFuerza['observaciones'] ?? null,
                ]
            );
        } else {
            $servicio->estadoFuerza()->delete();
        }

        $servicio->participantes()->delete();

        foreach (($validatedData['participantes'] ?? []) as $participante) {
            if (empty($participante['institucion']) && empty($participante['responsable']) && empty($participante['descripcion'])) {
                continue;
            }

            $servicio->participantes()->create([
                'institucion' => $this->normalizeText($participante['institucion'] ?? null),
                'responsable' => $this->normalizeText($participante['responsable'] ?? null),
                'elementos' => $participante['elementos'] ?? null,
                'vehiculos' => $participante['vehiculos'] ?? null,
                'unidad_identificador' => $this->normalizeText($participante['unidad_identificador'] ?? null),
                'descripcion' => $participante['descripcion'] ?? null,
            ]);
        }

        $servicio->coordenadas()->delete();

        foreach (($validatedData['coordenadas'] ?? []) as $index => $coordenada) {
            if (($coordenada['lat'] ?? null) === null || ($coordenada['lng'] ?? null) === null) {
                continue;
            }

            $servicio->coordenadas()->create([
                'lat' => $coordenada['lat'],
                'lng' => $coordenada['lng'],
                'descripcion' => $coordenada['descripcion'] ?? null,
                'orden' => $coordenada['orden'] ?? ($index + 1),
            ]);
        }

        $servicio->recursos()->delete();

        foreach (($validatedData['recursos'] ?? []) as $recurso) {
            if (empty($recurso['tipo_recurso']) && empty($recurso['descripcion'])) {
                continue;
            }

            $servicio->recursos()->create([
                'tipo_recurso' => $this->normalizeText($recurso['tipo_recurso'] ?? null),
                'descripcion' => $this->normalizeText($recurso['descripcion'] ?? null),
                'cantidad' => $recurso['cantidad'] ?? 1,
            ]);
        }
    }

    public function index(Request $request)
    {
        $fecha = $request->input('fecha');

        $query = Servicio::query()
            ->with([
                'creador',
                'personal',
                'canino',
                'equino',
                'patrulla',
                'estadoFuerza',
            ])
            ->orderByDesc('fecha')
            ->orderByDesc('hora');

        if ($fecha) {
            $query->whereDate('fecha', $fecha);
        }

        $servicios = $query->get();

        return response()->json([
            'ok' => true,
            'data' => $servicios,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validationRules());
        $validatedData = $this->normalizeValidatedData($validatedData);
        $validatedData['created_by'] = Auth::id();

        DB::beginTransaction();

        try {
            $servicio = Servicio::create($validatedData);

            $this->syncServicioDetalles($servicio, $validatedData);

            $servicio->load([
                'creador',
                'personal',
                'canino',
                'equino',
                'patrulla',
                'estadoFuerza',
                'participantes',
                'coordenadas',
                'recursos',
            ]);

            DB::commit();

            Log::info('Servicio API creado correctamente.', [
                'servicio_id' => $servicio->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Servicio creado correctamente.',
                'data' => $servicio,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear servicio desde API.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => app()->environment('local')
                    ? 'Error al crear el servicio: ' . $e->getMessage()
                    : 'Hubo un error al crear el servicio.',
            ], 500);
        }
    }

    public function show($id)
    {
        $servicio = Servicio::query()
            ->with([
                'creador',
                'personal',
                'canino',
                'equino',
                'patrulla',
                'estadoFuerza',
                'participantes',
                'coordenadas',
                'recursos',
                'reportes.creador',
                'reportes.fotos',
            ])
            ->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $servicio,
        ]);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::query()->findOrFail($id);

        $validatedData = $request->validate($this->validationRules());
        $validatedData = $this->normalizeValidatedData($validatedData);

        DB::beginTransaction();

        try {
            $servicio->update($validatedData);

            $this->syncServicioDetalles($servicio, $validatedData);

            $servicio->load([
                'creador',
                'personal',
                'canino',
                'equino',
                'patrulla',
                'estadoFuerza',
                'participantes',
                'coordenadas',
                'recursos',
                'reportes.creador',
                'reportes.fotos',
            ]);

            DB::commit();

            Log::info('Servicio API actualizado correctamente.', [
                'servicio_id' => $servicio->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Servicio actualizado correctamente.',
                'data' => $servicio,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al actualizar servicio desde API.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'servicio_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => app()->environment('local')
                    ? 'Error al actualizar el servicio: ' . $e->getMessage()
                    : 'Hubo un error al actualizar el servicio.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $servicio = Servicio::query()->findOrFail($id);
            $idServicio = $servicio->id;

            $servicio->delete();

            Log::info('Servicio API eliminado correctamente.', [
                'servicio_id' => $idServicio,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Servicio eliminado correctamente.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar servicio desde API.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'servicio_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => app()->environment('local')
                    ? 'Error al eliminar el servicio: ' . $e->getMessage()
                    : 'Hubo un error al eliminar el servicio.',
            ], 500);
        }
    }
}

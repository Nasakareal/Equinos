<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use App\Models\Personal;
use App\Models\PatrolAssignment;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use App\Services\TurnoActual;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatrolAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = PatrolAssignment::query()->with(['patrol', 'turno', 'creador', 'personals'])->orderBy('fecha', 'desc')->orderBy('id', 'desc');

        if ($request->filled('fecha')) $query->whereDate('fecha', $request->query('fecha'));
        if ($request->filled('turno_id')) $query->where('turno_id', (int) $request->query('turno_id'));
        if ($request->filled('patrol_id')) $query->where('patrol_id', (int) $request->query('patrol_id'));

        $assignments = $query->paginate((int) ($request->query('per_page', 20)));

        return response()->json([
            'ok' => true,
            'data' => $assignments->items(),
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
                'last_page' => $assignments->lastPage(),
            ],
        ]);
    }

    public function create(Request $request)
    {
        $tz = 'America/Mexico_City';

        $turno_id = (int) (TurnoActual::getTurnoActualId() ?? 0);
        if (!$turno_id) {
            return response()->json([
                'ok' => false,
                'message' => 'Primero define el turno en servicio en Configuración.',
            ], 422);
        }

        $turno = Turno::query()->find($turno_id);
        if (!$turno || !in_array((string) $turno->clave, ['A', 'B'], true)) {
            return response()->json([
                'ok' => false,
                'message' => 'El turno en servicio debe ser A o B.',
            ], 422);
        }

        $fecha = (string) $request->query('fecha', Carbon::now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) $fecha = Carbon::now($tz)->toDateString();

        $dia = Carbon::parse($fecha, $tz)->startOfDay();
        $now = Carbon::now($tz);
        $instante = $dia->copy()->setTime((int) $now->format('H'), (int) $now->format('i'), (int) $now->format('s'));

        $ya_asignados_personal_ids = PatrolAssignment::query()
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->with('personals:id')
            ->get()
            ->pluck('personals')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        $patrols_ya_usadas_ids = PatrolAssignment::query()
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->pluck('patrol_id')
            ->unique()
            ->values()
            ->toArray();

        $patrols = Patrol::query()->whereNotIn('id', $patrols_ya_usadas_ids)->orderBy('numero_economico')->get();

        $schedules = ServiceSchedule::query()->where('activo', 1)->where('tipo', 'CICLICO')->where('turno_id', $turno_id)->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if (!$sc->personal_id) continue;
            if ($this->estaLaborando($sc, $instante)) $laborando_ids[] = (int) $sc->personal_id;
        }

        $laborando_ids = array_values(array_unique($laborando_ids));
        $laborando_ids = array_values(array_diff($laborando_ids, $ya_asignados_personal_ids));

        $personals = Personal::query()->where('activo', 1)->whereIn('id', $laborando_ids)->orderBy('nombres')->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'fecha' => $fecha,
                'turno' => $turno,
                'turno_id' => (int) $turno_id,
                'patrols' => $patrols,
                'personals' => $personals,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $tz = 'America/Mexico_City';

        $turno_id = (int) (TurnoActual::getTurnoActualId() ?? 0);
        if (!$turno_id) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay turno en servicio configurado.',
            ], 422);
        }

        $turno = Turno::query()->find($turno_id);
        if (!$turno || !in_array((string) $turno->clave, ['A', 'B'], true)) {
            return response()->json([
                'ok' => false,
                'message' => 'El turno en servicio debe ser A o B.',
            ], 422);
        }

        $data = $request->validate([
            'patrol_id' => 'required|exists:patrols,id',
            'fecha' => 'required|date',
            'servicio' => 'nullable|string|max:255',
            'zona' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'encargado_id' => 'required|integer|exists:personals,id',
            'agregados' => 'nullable|array',
            'agregados.*' => 'integer|exists:personals,id',
        ]);

        $fecha = Carbon::parse($data['fecha'], $tz)->toDateString();

        $existsPatrol = PatrolAssignment::query()->where('patrol_id', (int) $data['patrol_id'])->whereDate('fecha', $fecha)->where('turno_id', $turno_id)->exists();
        if ($existsPatrol) {
            return response()->json([
                'ok' => false,
                'message' => 'Esa patrulla ya tiene asignación en esa fecha para ese turno.',
                'errors' => ['patrol_id' => ['Esa patrulla ya tiene asignación en esa fecha para ese turno.']],
            ], 422);
        }

        $ya_asignados_personal_ids = PatrolAssignment::query()
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->with('personals:id')
            ->get()
            ->pluck('personals')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        $dia = Carbon::parse($fecha, $tz)->startOfDay();
        $now = Carbon::now($tz);
        $instante = $dia->copy()->setTime((int) $now->format('H'), (int) $now->format('i'), (int) $now->format('s'));

        $schedules = ServiceSchedule::query()->where('activo', 1)->where('tipo', 'CICLICO')->where('turno_id', $turno_id)->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if (!$sc->personal_id) continue;
            if ($this->estaLaborando($sc, $instante)) $laborando_ids[] = (int) $sc->personal_id;
        }
        $laborando_ids = array_values(array_unique($laborando_ids));

        $encargadoId = (int) ($data['encargado_id'] ?? 0);

        $agregadosIds = $data['agregados'] ?? [];
        $agregadosIds = array_values(array_unique(array_map('intval', $agregadosIds)));
        $agregadosIds = array_values(array_filter($agregadosIds, fn($x) => $x > 0));
        $agregadosIds = array_values(array_diff($agregadosIds, [$encargadoId]));

        $personalsReq = array_values(array_unique(array_merge([$encargadoId], $agregadosIds)));

        $invalidos = array_values(array_diff($personalsReq, $laborando_ids));
        if (!empty($invalidos)) {
            return response()->json([
                'ok' => false,
                'message' => 'Hay personal seleccionado que no está laborando en este turno.',
                'errors' => ['encargado_id' => ['Hay personal seleccionado que no está laborando en este turno.']],
            ], 422);
        }

        $repetidos = array_values(array_intersect($personalsReq, $ya_asignados_personal_ids));
        if (!empty($repetidos)) {
            return response()->json([
                'ok' => false,
                'message' => 'Hay personal seleccionado que ya está asignado a otra unidad en esa fecha/turno.',
                'errors' => ['encargado_id' => ['Hay personal seleccionado que ya está asignado a otra unidad en esa fecha/turno.']],
            ], 422);
        }

        $assignment = PatrolAssignment::create([
            'patrol_id' => (int) $data['patrol_id'],
            'fecha' => $fecha,
            'turno_id' => $turno_id,
            'created_by' => auth()->id(),
            'servicio' => $data['servicio'] ?? null,
            'zona' => $data['zona'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $syncData = [];
        $syncData[$encargadoId] = ['rol' => 'ENCARGADO'];
        foreach ($agregadosIds as $pid) $syncData[(int) $pid] = ['rol' => 'AGREGADO'];

        $assignment->personals()->sync($syncData);

        $assignment->load(['patrol', 'turno', 'creador', 'personals']);

        return response()->json([
            'ok' => true,
            'message' => 'Asignación creada correctamente.',
            'data' => $assignment,
        ], 201);
    }

    public function show(PatrolAssignment $patrol_assignment)
    {
        $patrol_assignment->load(['patrol', 'turno', 'creador', 'personals']);

        return response()->json([
            'ok' => true,
            'data' => $patrol_assignment,
        ]);
    }

    public function update(Request $request, PatrolAssignment $patrol_assignment)
    {
        $tz = 'America/Mexico_City';

        $data = $request->validate([
            'patrol_id' => 'required|exists:patrols,id',
            'fecha' => 'required|date',
            'servicio' => 'nullable|string|max:255',
            'zona' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'encargado_id' => 'nullable|integer|exists:personals,id',
            'agregados' => 'nullable|array',
            'agregados.*' => 'integer|exists:personals,id',
        ]);

        $fecha = Carbon::parse($data['fecha'], $tz)->toDateString();

        $existsPatrol = PatrolAssignment::query()
            ->where('patrol_id', (int) $data['patrol_id'])
            ->whereDate('fecha', $fecha)
            ->where('turno_id', (int) $patrol_assignment->turno_id)
            ->where('id', '!=', $patrol_assignment->id)
            ->exists();

        if ($existsPatrol) {
            return response()->json([
                'ok' => false,
                'message' => 'Esa patrulla ya tiene asignación en esa fecha para ese turno.',
                'errors' => ['patrol_id' => ['Esa patrulla ya tiene asignación en esa fecha para ese turno.']],
            ], 422);
        }

        $patrol_assignment->update([
            'patrol_id' => (int) $data['patrol_id'],
            'fecha' => $fecha,
            'servicio' => $data['servicio'] ?? null,
            'zona' => $data['zona'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $encargadoId = array_key_exists('encargado_id', $data) ? (int) ($data['encargado_id'] ?? 0) : 0;

        $agregadosIds = $data['agregados'] ?? null;

        if ($encargadoId > 0 || is_array($agregadosIds)) {
            $agregadosIds = $agregadosIds ?? [];
            $agregadosIds = array_values(array_unique(array_map('intval', $agregadosIds)));
            $agregadosIds = array_values(array_filter($agregadosIds, fn($x) => $x > 0));
            $agregadosIds = array_values(array_diff($agregadosIds, [$encargadoId]));

            $syncData = [];
            if ($encargadoId > 0) $syncData[$encargadoId] = ['rol' => 'ENCARGADO'];
            foreach ($agregadosIds as $pid) $syncData[(int) $pid] = ['rol' => 'AGREGADO'];

            if (!empty($syncData)) $patrol_assignment->personals()->sync($syncData);
        }

        $patrol_assignment->load(['patrol', 'turno', 'creador', 'personals']);

        return response()->json([
            'ok' => true,
            'message' => 'Asignación actualizada correctamente.',
            'data' => $patrol_assignment,
        ]);
    }

    public function destroy(PatrolAssignment $patrol_assignment)
    {
        $patrol_assignment->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Asignación eliminada correctamente.',
        ]);
    }

    private function estaLaborando(ServiceSchedule $sc, Carbon $now): bool
    {
        if (empty($sc->fecha_inicio_ciclo)) return false;

        $inicio = Carbon::parse($sc->fecha_inicio_ciclo, 'America/Mexico_City')->setTime(7, 0, 0);
        if ($now->lt($inicio)) return false;

        $horas_trabajo = (int) $sc->horas_trabajo;
        $horas_descanso = (int) $sc->horas_descanso;

        if ($horas_trabajo <= 0) return false;
        if ($horas_descanso < 0) $horas_descanso = 0;

        $ciclo = $horas_trabajo + $horas_descanso;
        if ($ciclo <= 0) return false;

        $diffHoras = $inicio->diffInHours($now);
        $pos = $diffHoras % $ciclo;

        return $pos < $horas_trabajo;
    }
}

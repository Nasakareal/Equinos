<?php

namespace App\Http\Controllers;

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
        $query = PatrolAssignment::with(['patrol', 'turno', 'creador', 'personals'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->input('fecha'));
        }

        if ($request->filled('turno_id')) {
            $query->where('turno_id', $request->input('turno_id'));
        }

        if ($request->filled('patrol_id')) {
            $query->where('patrol_id', $request->input('patrol_id'));
        }

        $assignments = $query->paginate(20)->appends($request->query());

        $turnos = Turno::query()
            ->where('activo', 1)
            ->whereIn('clave', ['A', 'B'])
            ->orderBy('clave')
            ->get();

        $patrols = Patrol::orderBy('numero_economico')->get();

        return view('patrullas_asignaciones.index', compact('assignments', 'turnos', 'patrols'));
    }

    public function create(Request $request)
    {
        $turno_id = (int) (TurnoActual::getTurnoActualId() ?? 0);
        if (!$turno_id) {
            return redirect()->route('settings.turno_actual')->with('error', 'Primero define el turno en servicio en Configuración.');
        }

        $turno = Turno::query()->find($turno_id);
        if (!$turno || !in_array((string)$turno->clave, ['A','B'], true)) {
            return redirect()->route('settings.turno_actual')->with('error', 'El turno en servicio debe ser A o B.');
        }

        $fecha = $request->query('fecha', Carbon::now('America/Mexico_City')->toDateString());
        $dia = Carbon::parse($fecha, 'America/Mexico_City')->startOfDay();
        $now = Carbon::now('America/Mexico_City');

        $instante = $dia->copy()->setTime(
            (int)$now->format('H'),
            (int)$now->format('i'),
            (int)$now->format('s')
        );

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

        $patrols = Patrol::query()
            ->whereNotIn('id', $patrols_ya_usadas_ids)
            ->orderBy('numero_economico')
            ->get();

        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->where('turno_id', $turno_id)
            ->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if (!$sc->personal_id) continue;
            if ($this->estaLaborando($sc, $instante)) {
                $laborando_ids[] = (int)$sc->personal_id;
            }
        }

        $laborando_ids = array_values(array_unique($laborando_ids));
        $laborando_ids = array_values(array_diff($laborando_ids, $ya_asignados_personal_ids));

        $personals = Personal::query()
            ->where('activo', true)
            ->whereIn('id', $laborando_ids)
            ->orderBy('nombres')
            ->get();

        return view('patrullas_asignaciones.create', compact(
            'patrols',
            'turno',
            'turno_id',
            'fecha',
            'personals'
        ));
    }

    public function store(Request $request)
    {
        $turno_id = (int) (TurnoActual::getTurnoActualId() ?? 0);
        if (!$turno_id) {
            return back()->withInput()->withErrors(['turno_id' => 'No hay turno en servicio configurado.']);
        }

        $turno = Turno::query()->find($turno_id);
        if (!$turno || !in_array((string)$turno->clave, ['A','B'], true)) {
            return back()->withInput()->withErrors(['turno_id' => 'El turno en servicio debe ser A o B.']);
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

        $fecha = Carbon::parse($data['fecha'], 'America/Mexico_City')->toDateString();

        $existsPatrol = PatrolAssignment::query()
            ->where('patrol_id', (int)$data['patrol_id'])
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->exists();

        if ($existsPatrol) {
            return back()->withInput()->withErrors([
                'patrol_id' => 'Esa patrulla ya tiene asignación en esa fecha para ese turno.'
            ]);
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

        $dia = Carbon::parse($fecha, 'America/Mexico_City')->startOfDay();
        $now = Carbon::now('America/Mexico_City');

        $instante = $dia->copy()->setTime(
            (int)$now->format('H'),
            (int)$now->format('i'),
            (int)$now->format('s')
        );

        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->where('turno_id', $turno_id)
            ->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if (!$sc->personal_id) continue;
            if ($this->estaLaborando($sc, $instante)) {
                $laborando_ids[] = (int)$sc->personal_id;
            }
        }
        $laborando_ids = array_values(array_unique($laborando_ids));

        $encargadoId = (int)($data['encargado_id'] ?? 0);
        $agregadosIds = $data['agregados'] ?? [];
        $agregadosIds = array_values(array_unique(array_map('intval', $agregadosIds)));
        $agregadosIds = array_values(array_filter($agregadosIds, fn($x) => $x > 0));
        $agregadosIds = array_values(array_diff($agregadosIds, [$encargadoId]));

        $personalsReq = array_values(array_unique(array_merge([$encargadoId], $agregadosIds)));

        $invalidos = array_values(array_diff($personalsReq, $laborando_ids));
        if (!empty($invalidos)) {
            return back()->withInput()->withErrors([
                'encargado_id' => 'Hay personal seleccionado que no está laborando en este turno.'
            ]);
        }

        $repetidos = array_values(array_intersect($personalsReq, $ya_asignados_personal_ids));
        if (!empty($repetidos)) {
            return back()->withInput()->withErrors([
                'encargado_id' => 'Hay personal seleccionado que ya está asignado a otra unidad en esa fecha/turno.'
            ]);
        }

        $assignment = PatrolAssignment::create([
            'patrol_id' => (int)$data['patrol_id'],
            'fecha' => $fecha,
            'turno_id' => $turno_id,
            'created_by' => auth()->id(),
            'servicio' => $data['servicio'] ?? null,
            'zona' => $data['zona'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $syncData = [];
        $syncData[$encargadoId] = ['rol' => 'ENCARGADO'];
        foreach ($agregadosIds as $pid) {
            $syncData[(int)$pid] = ['rol' => 'AGREGADO'];
        }

        $assignment->personals()->sync($syncData);

        return redirect()->route('patrullas_asignaciones.index')->with('success', 'Asignación creada correctamente.');
    }

    public function show(PatrolAssignment $patrol_assignment)
    {
        $patrol_assignment->load(['patrol', 'turno', 'creador', 'personals']);
        return view('patrullas_asignaciones.show', compact('patrol_assignment'));
    }

    public function edit(PatrolAssignment $patrol_assignment)
    {
        $patrol_assignment->load(['personals']);

        $turno_id = (int)($patrol_assignment->turno_id ?: 0);
        $turno = $turno_id ? Turno::query()->find($turno_id) : null;

        $fecha = Carbon::parse($patrol_assignment->fecha, 'America/Mexico_City')->toDateString();

        $ya_asignados_personal_ids = PatrolAssignment::query()
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->where('id', '!=', $patrol_assignment->id)
            ->with('personals:id')
            ->get()
            ->pluck('personals')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        $patrols = Patrol::orderBy('numero_economico')->get();

        $dia = Carbon::parse($fecha, 'America/Mexico_City')->startOfDay();
        $now = Carbon::now('America/Mexico_City');
        $instante = $dia->copy()->setTime((int)$now->format('H'), (int)$now->format('i'), (int)$now->format('s'));

        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->where('turno_id', $turno_id)
            ->get();

        $laborando_ids = [];
        foreach ($schedules as $sc) {
            if (!$sc->personal_id) continue;
            if ($this->estaLaborando($sc, $instante)) {
                $laborando_ids[] = (int)$sc->personal_id;
            }
        }

        $laborando_ids = array_values(array_unique($laborando_ids));
        $laborando_ids = array_values(array_diff($laborando_ids, $ya_asignados_personal_ids));

        $personals = Personal::query()
            ->where('activo', true)
            ->whereIn('id', $laborando_ids)
            ->orderBy('nombres')
            ->get();

        return view('patrullas_asignaciones.edit', compact('patrol_assignment', 'patrols', 'turno', 'turno_id', 'fecha', 'personals'));
    }

    public function update(Request $request, PatrolAssignment $patrol_assignment)
    {
        $data = $request->validate([
            'patrol_id' => 'required|exists:patrols,id',
            'fecha' => 'required|date',
            'servicio' => 'nullable|string|max:255',
            'zona' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'personals' => 'nullable|array',
            'personals.*' => 'exists:personals,id',
        ]);

        $fecha = Carbon::parse($data['fecha'], 'America/Mexico_City')->toDateString();

        $existsPatrol = PatrolAssignment::query()
            ->where('patrol_id', (int)$data['patrol_id'])
            ->whereDate('fecha', $fecha)
            ->where('turno_id', (int)$patrol_assignment->turno_id)
            ->where('id', '!=', $patrol_assignment->id)
            ->exists();

        if ($existsPatrol) {
            return back()->withInput()->withErrors([
                'patrol_id' => 'Esa patrulla ya tiene asignación en esa fecha para ese turno.'
            ]);
        }

        $patrol_assignment->update([
            'patrol_id' => (int)$data['patrol_id'],
            'fecha' => $fecha,
            'servicio' => $data['servicio'] ?? null,
            'zona' => $data['zona'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $personalsReq = $data['personals'] ?? [];
        $personalsReq = array_values(array_unique(array_map('intval', $personalsReq)));

        $syncData = [];
        foreach ($personalsReq as $personalId) {
            $syncData[$personalId] = ['rol' => null];
        }

        $patrol_assignment->personals()->sync($syncData);

        return redirect()->route('patrullas_asignaciones.index')->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy(PatrolAssignment $patrol_assignment)
    {
        $patrol_assignment->delete();
        return redirect()->route('patrullas_asignaciones.index')->with('success', 'Asignación eliminada correctamente.');
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

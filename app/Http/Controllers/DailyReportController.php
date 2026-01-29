<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyReportRow;
use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use App\Services\DailyReports\Exporters\ArmamentoExcelExporter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $reportes = DailyReport::query()
            ->with(['turno', 'generadoPor'])
            ->orderByDesc('fecha')
            ->orderByDesc('created_at')
            ->get();

        $now = Carbon::now('America/Mexico_City');
        $fecha_operativa = $this->getFechaOperativa($now);
        $turno_en_servicio_id = $this->getTurnoEnServicioId($now);
        $turnos = Turno::query()->orderBy('id')->get();
        $estado_fuerza = $this->getEstadoDeFuerza($now, $turno_en_servicio_id);

        return view('daily_reports.index', compact(
            'reportes',
            'now',
            'fecha_operativa',
            'turno_en_servicio_id',
            'turnos',
            'estado_fuerza'
        ));
    }

    public function generar(Request $request)
    {
        $now = Carbon::now('America/Mexico_City');

        $turno_id = $request->filled('turno_id')
            ? (int)$request->turno_id
            : (int)($this->getTurnoEnServicioId($now) ?? 0);

        if (empty($turno_id)) {
            return back()->with('error', 'No se pudo detectar el turno en servicio. Revisa service_schedules.');
        }

        $fecha_operativa = $this->getFechaOperativa($now);
        $fecha = $fecha_operativa->toDateString();

        $tipo_reporte = $request->filled('tipo_reporte') ? (string)$request->tipo_reporte : 'ESTADO_FUERZA';
        $notas = $request->filled('notas') ? (string)$request->notas : null;

        $ya_existe = DailyReport::query()
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->where('tipo_reporte', $tipo_reporte)
            ->exists();

        if ($ya_existe) {
            return back()->with('error', 'Ya existe un reporte para hoy (fecha operativa), ese turno y ese tipo.');
        }

        DB::beginTransaction();
        try {
            $reporte = DailyReport::create([
                'fecha' => $fecha,
                'tipo_reporte' => $tipo_reporte,
                'turno_id' => $turno_id,
                'generado_por' => Auth::id(),
                'notas' => $notas,
            ]);

            $estado_fuerza = $this->getEstadoDeFuerza($now, $turno_id);

            $orden = 1;

            foreach ($estado_fuerza['personals_laborando'] as $p) {
                $armas = $this->getArmamentoActual($p->id, $fecha_operativa);

                DailyReportRow::create([
                    'daily_report_id' => $reporte->id,
                    'personal_id' => $p->id,

                    'grado' => $p->grado,
                    'cuip' => $p->cuip,
                    'nombre' => $p->nombres,
                    'dependencia' => $p->dependencia,

                    'arma_corta' => $armas['arma_corta'],
                    'matricula_corta' => $armas['matricula_corta'],
                    'arma_larga' => $armas['arma_larga'],
                    'matricula_larga' => $armas['matricula_larga'],

                    'incidencia' => null,
                    'celular' => $p->celular,
                    'cargo' => $p->cargo,
                    'crp' => $p->crp,
                    'area_sector' => $p->area_patrullaje,

                    'hora_entrada' => null,
                    'firma_entrada' => null,
                    'hora_salida' => null,
                    'firma_salida' => null,
                    'despliegue_servicio' => null,

                    'observaciones' => $p->observaciones,
                    'orden' => $orden++,
                ]);
            }

            foreach ($estado_fuerza['personals_descanso'] as $p) {
                $armas = $this->getArmamentoActual($p->id, $fecha_operativa);

                DailyReportRow::create([
                    'daily_report_id' => $reporte->id,
                    'personal_id' => $p->id,

                    'grado' => $p->grado,
                    'cuip' => $p->cuip,
                    'nombre' => $p->nombres,
                    'dependencia' => $p->dependencia,

                    'arma_corta' => $armas['arma_corta'],
                    'matricula_corta' => $armas['matricula_corta'],
                    'arma_larga' => $armas['arma_larga'],
                    'matricula_larga' => $armas['matricula_larga'],

                    'incidencia' => null,
                    'celular' => $p->celular,
                    'cargo' => $p->cargo,
                    'crp' => $p->crp,
                    'area_sector' => $p->area_patrullaje,

                    'hora_entrada' => null,
                    'firma_entrada' => null,
                    'hora_salida' => null,
                    'firma_salida' => null,
                    'despliegue_servicio' => null,

                    'observaciones' => $p->observaciones,
                    'orden' => $orden++,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('daily_reports.show', $reporte->id)
                ->with('success', 'Reporte generado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function show(DailyReport $daily_report)
    {
        $daily_report->load([
            'turno',
            'generadoPor',
            'rows' => function ($q) {
                $q->with('personal')
                    ->orderBy('dependencia')
                    ->orderBy('orden')
                    ->orderBy('id');
            }
        ]);

        $totales = [
            'total_filas' => $daily_report->rows->count(),
            'por_dependencia' => $daily_report->rows
                ->groupBy('dependencia')
                ->map(fn($items) => ['total' => $items->count()]),
        ];

        return view('daily_reports.show', compact('daily_report', 'totales'));
    }

    public function descargar(Request $request, DailyReport $daily_report, string $tipo)
    {
        if ($tipo === 'excel_armamento') {
            $dependencia = $request->query('dependencia');
            return app(ArmamentoExcelExporter::class)->download($daily_report, $dependencia);
        }

        return back()->with('error', 'Descarga "' . $tipo . '" todavía no implementada.');
    }

    private function getFechaOperativa(Carbon $now): Carbon
    {
        $corte = $now->copy()->setTime(7, 0, 0);
        return $now->lt($corte)
            ? $now->copy()->subDay()->startOfDay()
            : $now->copy()->startOfDay();
    }

    private function getTurnoEnServicioId(Carbon $now): ?int
    {
        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->orderByDesc('fecha_inicio_ciclo')
            ->get();

        foreach ($schedules as $sc) {
            if ($this->estaLaborando($sc, $now)) {
                return (int)$sc->turno_id;
            }
        }

        $fallback = $schedules->first();
        return $fallback ? (int)$fallback->turno_id : null;
    }

    private function getEstadoDeFuerza(Carbon $now, ?int $turno_id): array
    {
        if (empty($turno_id)) {
            return [
                'personals_laborando' => collect(),
                'personals_descanso' => collect(),
                'totales' => ['laborando' => 0, 'descanso' => 0],
                'por_dependencia' => collect(),
            ];
        }

        $schedules = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->where('turno_id', $turno_id)
            ->get();

        $personal_ids = $schedules->pluck('personal_id')->unique()->values();

        $personals = Personal::query()
            ->whereIn('id', $personal_ids)
            ->orderBy('dependencia')
            ->orderBy('nombres')
            ->get()
            ->keyBy('id');

        $laborando = collect();
        $descanso = collect();

        foreach ($schedules as $sc) {
            $p = $personals->get($sc->personal_id);
            if (!$p) continue;

            if ($this->estaLaborando($sc, $now)) $laborando->push($p);
            else $descanso->push($p);
        }

        $por_dependencia = $personals->values()
            ->groupBy('dependencia')
            ->map(function ($items) use ($laborando) {
                $ids_laborando = $laborando->pluck('id')->flip();
                $lab = $items->filter(fn($p) => $ids_laborando->has($p->id))->count();
                $des = $items->count() - $lab;

                return [
                    'laborando' => $lab,
                    'descanso' => $des,
                    'total' => $items->count(),
                ];
            });

        return [
            'personals_laborando' => $laborando->unique('id')->values(),
            'personals_descanso' => $descanso->unique('id')->values(),
            'totales' => [
                'laborando' => $laborando->unique('id')->count(),
                'descanso' => $descanso->unique('id')->count(),
            ],
            'por_dependencia' => $por_dependencia,
        ];
    }

    private function estaLaborando(ServiceSchedule $sc, Carbon $now): bool
    {
        $inicio = Carbon::parse($sc->fecha_inicio_ciclo, 'America/Mexico_City')->setTime(7, 0, 0);
        if ($now->lt($inicio)) return false;

        $horas_trabajo = (int)$sc->horas_trabajo;
        $horas_descanso = (int)$sc->horas_descanso;

        if ($horas_trabajo <= 0) return false;
        if ($horas_descanso < 0) $horas_descanso = 0;

        $ciclo = $horas_trabajo + $horas_descanso;
        if ($ciclo <= 0) return false;

        $diffHoras = $inicio->diffInHours($now);
        $pos = $diffHoras % $ciclo;

        return $pos < $horas_trabajo;
    }

    private function getArmamentoActual(int $personal_id, Carbon $fecha_operativa): array
    {
        $fecha = $fecha_operativa->toDateString();

        $rows = DB::table('weapon_assignments as wa')
            ->join('weapons as w', 'w.id', '=', 'wa.weapon_id')
            ->where('wa.personal_id', $personal_id)
            ->where('wa.status', 'ASIGNADA')
            ->whereDate('wa.fecha_asignacion', '<=', $fecha)
            ->where(function ($q) use ($fecha) {
                $q->whereNull('wa.fecha_devolucion')
                    ->orWhereDate('wa.fecha_devolucion', '>=', $fecha);
            })
            ->select('w.tipo', 'w.marca_modelo', 'w.matricula')
            ->get();

        $arma_corta = null;
        $matricula_corta = null;
        $arma_larga = null;
        $matricula_larga = null;

        foreach ($rows as $r) {
            $tipo = strtoupper(trim((string)$r->tipo));
            if ($tipo === 'CORTA') {
                $arma_corta = $r->marca_modelo ?: 'CORTA';
                $matricula_corta = $r->matricula;
            } elseif ($tipo === 'LARGA') {
                $arma_larga = $r->marca_modelo ?: 'LARGA';
                $matricula_larga = $r->matricula;
            }
        }

        return [
            'arma_corta' => $arma_corta,
            'matricula_corta' => $matricula_corta,
            'arma_larga' => $arma_larga,
            'matricula_larga' => $matricula_larga,
        ];
    }
}

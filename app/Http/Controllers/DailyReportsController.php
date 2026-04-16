<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Services\DailyReports\DailyReportsService;
use Illuminate\Http\Request;

class DailyReportsController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $this->normalizarFecha($request->query('fecha'));
        $turno_id = $this->resolverTurnoId($request->query('turno_id'));

        return view('daily_reports.index', compact('fecha', 'turno_id'));
    }

    public function indexEstadoFuerza(Request $request)
    {
        return $this->indexPorTipo($request, 'estado_fuerza', 'daily_reports.estado_fuerza.index');
    }

    public function indexListaPersonal(Request $request)
    {
        return $this->indexPorTipo($request, 'lista_personal', 'daily_reports.lista_personal.index');
    }

    public function indexPaseListaCanina(Request $request)
    {
        return $this->indexPorTipo($request, 'pase_lista_canina', 'daily_reports.pase_lista_canina.index');
    }

    public function indexPaseListaAgrupamientoEquinosCaninos(Request $request)
    {
        return $this->indexPorTipo($request, 'pase_lista_agrupamiento_equinos_caninos', 'daily_reports.pase_lista_agrupamiento_equinos_caninos.index');
    }

    public function indexArmamentoEquinosCaninos(Request $request)
    {
        return $this->indexPorTipo($request, 'armamento_equinos_caninos', 'daily_reports.armamento_equinos_caninos.index');
    }

    public function show(DailyReport $daily_report)
    {
        return view('daily_reports.show', compact('daily_report'));
    }

    public function descargar(DailyReport $daily_report, string $tipo, Request $request, DailyReportsService $service)
    {
        $params = $request->all();

        return $service->descargarDesdeRegistro($daily_report, $tipo, $params);
    }

    public function descargarExcelArmamento(DailyReport $daily_report, Request $request, DailyReportsService $service)
    {
        $params = $request->all();

        return $service->descargarExcelArmamentoDesdeRegistro($daily_report, $params);
    }

    protected function indexPorTipo(Request $request, string $tipo, string $view)
    {
        $fecha_desde = $this->normalizarFecha($request->query('fecha_desde'));
        $fecha_hasta = $this->normalizarFecha($request->query('fecha_hasta'));
        $turno_id = $this->resolverTurnoId($request->query('turno_id'));

        $reportes = DailyReport::query()
            ->where('tipo', $tipo)
            ->when($fecha_desde, function ($query) use ($fecha_desde) {
                $query->whereDate('fecha', '>=', $fecha_desde);
            })
            ->when($fecha_hasta, function ($query) use ($fecha_hasta) {
                $query->whereDate('fecha', '<=', $fecha_hasta);
            })
            ->when($turno_id > 0, function ($query) use ($turno_id) {
                $query->where('turno_id', $turno_id);
            })
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view($view, compact('reportes', 'tipo', 'fecha_desde', 'fecha_hasta', 'turno_id'));
    }

    protected function normalizarFecha(?string $fecha): ?string
    {
        $tz = 'America/Mexico_City';
        $fecha = (string) ($fecha ?: now($tz)->toDateString());

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return now($tz)->toDateString();
        }

        return $fecha;
    }

    protected function resolverTurnoId($turnoId): int
    {
        return (int) ($turnoId ?? (auth()->user()->turno_id ?? 0));
    }
}

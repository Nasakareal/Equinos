<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DailyReports\DailyReportsService;

class DailyReportsController extends Controller
{
    public function index(Request $request, DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->query('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $turno_id = (int) ($request->query('turno_id') ?? (auth()->user()->turno_id ?? 1));

        $tipos = $service->catalogoTipos();
        $estado = $service->estadoArchivos($fecha, $turno_id);

        return view('daily_reports.index', compact('fecha', 'turno_id', 'tipos', 'estado'));
    }

    public function descargar(Request $request, string $tipo, DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->query('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $turno_id = (int) ($request->query('turno_id') ?? (auth()->user()->turno_id ?? 1));

        $params = $request->all();

        return $service->descargar($tipo, $fecha, $turno_id, $params);
    }

    public function generar(Request $request, DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->input('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $turno_id = (int) ($request->input('turno_id') ?? (auth()->user()->turno_id ?? 1));

        $tipos = $request->input('tipos');
        $params = $request->all();

        $service->generarMultiples($fecha, $turno_id, $tipos ?: null, $params);

        return redirect()
            ->route('daily_reports.index', ['fecha' => $fecha, 'turno_id' => $turno_id])
            ->with('success', 'Listo. Reportes generados.');
    }
}

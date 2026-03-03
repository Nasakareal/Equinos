<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DailyReports\DailyReportsService;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request, DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->query('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $turno_id = (int) ($request->query('turno_id') ?? (auth()->user()->turno_id ?? 1));
        if ($turno_id <= 0) $turno_id = 1;

        $tipos = $service->catalogoTipos();
        $estado = $service->estadoArchivos($fecha, $turno_id);

        return response()->json([
            'ok' => true,
            'fecha' => $fecha,
            'turno_id' => $turno_id,
            'tipos' => $tipos,
            'estado' => $estado,
        ]);
    }

    public function descargar(Request $request, string $tipo, DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->query('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $turno_id = (int) ($request->query('turno_id') ?? (auth()->user()->turno_id ?? 1));
        if ($turno_id <= 0) $turno_id = 1;

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
        if ($turno_id <= 0) $turno_id = 1;

        $tipos = $request->input('tipos');
        if (is_string($tipos) && $tipos !== '') {
            $tipos = [$tipos];
        }
        if (!is_array($tipos)) {
            $tipos = null;
        }

        $params = $request->all();

        $service->generarMultiples($fecha, $turno_id, $tipos ?: null, $params);

        return response()->json([
            'ok' => true,
            'message' => 'Listo. Reportes generados.',
            'fecha' => $fecha,
            'turno_id' => $turno_id,
            'tipos' => $tipos,
        ]);
    }
}

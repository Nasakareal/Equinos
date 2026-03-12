<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EquinoterapiaReporte;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquinoterapiaReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = EquinoterapiaReporte::query()->withCount('registros');

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $reportes = $query->orderBy('fecha', 'desc')->paginate(15);
        $inicioSemana = $request->filled('semana_inicio') ? Carbon::parse($request->semana_inicio)->startOfWeek() : now()->startOfWeek();
        $finSemana = (clone $inicioSemana)->endOfWeek();
        $reportesSemana = EquinoterapiaReporte::with('registros')->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])->get();

        return response()->json([
            'ok' => true,
            'data' => $reportes,
            'resumen_semana' => [
                'inicio' => $inicioSemana->toDateString(),
                'fin' => $finSemana->toDateString(),
                'totales' => $this->calcularTotalesColeccion($reportesSemana),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date|unique:equinoterapia_reportes,fecha',
            'valoraciones' => 'nullable|integer|min:0',
            'personal' => 'nullable|integer|min:0',
            'equinos' => 'nullable|integer|min:0',
            'actividades_area' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'nombre_completo' => 'nullable|array',
            'nombre_completo.*' => 'nullable|string|max:255',
            'sexo' => 'nullable|array',
            'sexo.*' => 'nullable|in:NIÑO,NIÑA',
            'diagnostico' => 'nullable|array',
            'diagnostico.*' => 'nullable|string|max:255',
            'estatus_asistencia' => 'nullable|array',
            'estatus_asistencia.*' => 'nullable|in:ASISTIO,INASISTIO',
            'motivo_inasistencia' => 'nullable|array',
            'motivo_inasistencia.*' => 'nullable|string',
            'es_valoracion' => 'nullable|array',
            'es_valoracion.*' => 'nullable|in:0,1',
        ]);

        $reporte = DB::transaction(function () use ($request, $data) {
            $reporte = EquinoterapiaReporte::create([
                'fecha' => $data['fecha'],
                'valoraciones' => $data['valoraciones'] ?? 0,
                'personal' => $data['personal'] ?? 0,
                'equinos' => $data['equinos'] ?? 0,
                'actividades_area' => $data['actividades_area'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $this->guardarRegistros($reporte, $request);
            return $reporte->load('registros');
        });

        return response()->json(['ok' => true, 'message' => 'Reporte de equinoterapia registrado correctamente.', 'data' => $reporte], 201);
    }

    public function show(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->load('registros');
        $totales = $this->calcularTotalesReporte($equinoterapia);
        $mensajeWhatsapp = $this->generarMensajeWhatsapp($equinoterapia, $totales);

        return response()->json([
            'ok' => true,
            'data' => $equinoterapia,
            'totales' => $totales,
            'whatsapp' => [
                'mensaje' => $mensajeWhatsapp,
                'url' => 'https://wa.me/?text=' . urlencode($mensajeWhatsapp),
            ],
        ]);
    }

    public function update(Request $request, EquinoterapiaReporte $equinoterapia)
    {
        $data = $request->validate([
            'fecha' => 'required|date|unique:equinoterapia_reportes,fecha,' . $equinoterapia->id,
            'valoraciones' => 'nullable|integer|min:0',
            'personal' => 'nullable|integer|min:0',
            'equinos' => 'nullable|integer|min:0',
            'actividades_area' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'nombre_completo' => 'nullable|array',
            'nombre_completo.*' => 'nullable|string|max:255',
            'sexo' => 'nullable|array',
            'sexo.*' => 'nullable|in:NIÑO,NIÑA',
            'diagnostico' => 'nullable|array',
            'diagnostico.*' => 'nullable|string|max:255',
            'estatus_asistencia' => 'nullable|array',
            'estatus_asistencia.*' => 'nullable|in:ASISTIO,INASISTIO',
            'motivo_inasistencia' => 'nullable|array',
            'motivo_inasistencia.*' => 'nullable|string',
            'es_valoracion' => 'nullable|array',
            'es_valoracion.*' => 'nullable|in:0,1',
        ]);

        DB::transaction(function () use ($request, $data, $equinoterapia) {
            $equinoterapia->update([
                'fecha' => $data['fecha'],
                'valoraciones' => $data['valoraciones'] ?? 0,
                'personal' => $data['personal'] ?? 0,
                'equinos' => $data['equinos'] ?? 0,
                'actividades_area' => $data['actividades_area'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $equinoterapia->registros()->delete();
            $this->guardarRegistros($equinoterapia, $request);
        });

        return response()->json(['ok' => true, 'message' => 'Reporte de equinoterapia actualizado correctamente.', 'data' => $equinoterapia->fresh()->load('registros')]);
    }

    public function destroy(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->delete();
        return response()->json(['ok' => true, 'message' => 'Reporte de equinoterapia eliminado correctamente.']);
    }

    public function whatsapp(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->load('registros');
        $totales = $this->calcularTotalesReporte($equinoterapia);
        $mensajeWhatsapp = $this->generarMensajeWhatsapp($equinoterapia, $totales);

        return response()->json(['ok' => true, 'data' => ['mensaje' => $mensajeWhatsapp, 'url' => 'https://wa.me/?text=' . urlencode($mensajeWhatsapp)]]);
    }

    protected function guardarRegistros(EquinoterapiaReporte $reporte, Request $request): void
    {
        $nombres = $request->input('nombre_completo', []);
        $sexos = $request->input('sexo', []);
        $diagnosticos = $request->input('diagnostico', []);
        $estatuses = $request->input('estatus_asistencia', []);
        $motivos = $request->input('motivo_inasistencia', []);
        $valoraciones = $request->input('es_valoracion', []);

        foreach ($nombres as $i => $nombre) {
            $nombre = trim((string) $nombre);
            if ($nombre === '') {
                continue;
            }

            $estatus = $estatuses[$i] ?? 'ASISTIO';
            $motivo = $motivos[$i] ?? null;
            if ($estatus === 'ASISTIO') {
                $motivo = null;
            }

            $reporte->registros()->create([
                'nombre_completo' => mb_strtoupper($nombre, 'UTF-8'),
                'sexo' => $sexos[$i] ?? 'NIÑO',
                'diagnostico' => !empty($diagnosticos[$i]) ? mb_strtoupper($diagnosticos[$i], 'UTF-8') : null,
                'estatus_asistencia' => $estatus,
                'motivo_inasistencia' => !empty($motivo) ? mb_strtoupper($motivo, 'UTF-8') : null,
                'es_valoracion' => (int) ($valoraciones[$i] ?? 0) === 1,
            ]);
        }
    }

    protected function calcularTotalesReporte(EquinoterapiaReporte $reporte): array
    {
        $registros = $reporte->registros;

        return [
            'realizadas' => $registros->where('estatus_asistencia', 'ASISTIO')->where('es_valoracion', false)->count(),
            'inasistencias' => $registros->where('estatus_asistencia', 'INASISTIO')->count(),
            'ninas' => $registros->where('estatus_asistencia', 'ASISTIO')->where('sexo', 'NIÑA')->count(),
            'ninos' => $registros->where('estatus_asistencia', 'ASISTIO')->where('sexo', 'NIÑO')->count(),
            'valoraciones' => $registros->where('es_valoracion', true)->count(),
            'personal' => (int) $reporte->personal,
            'equinos' => (int) $reporte->equinos,
        ];
    }

    protected function calcularTotalesColeccion($reportes): array
    {
        $totales = ['realizadas' => 0, 'inasistencias' => 0, 'ninas' => 0, 'ninos' => 0, 'valoraciones' => 0, 'personal' => 0, 'equinos' => 0];

        foreach ($reportes as $reporte) {
            $actual = $this->calcularTotalesReporte($reporte);
            foreach ($totales as $key => $value) {
                $totales[$key] += $actual[$key];
            }
        }

        return $totales;
    }

    protected function generarMensajeWhatsapp(EquinoterapiaReporte $reporte, array $totales): string
    {
        $fechaTexto = Carbon::parse($reporte->fecha)->format('d/m/Y');

        return "COORDINACION DE AGRUPAMIENTOS\n\n"
            . "AGRUPAMIENTO DE EQUINOS Y CANINOS\n\n"
            . "AREA : EQUINOTERAPIA\n\n"
            . "PARA CONOCIMIENTO DEL MANDO GUARDIA CIVIL INFORMA:\n"
            . "Se Dan por concluidas las Equinoterapias por el dia de hoy\n"
            . $fechaTexto . ".\n\n"
            . "El cual se llevo acabo de la Siguiente manera.\n\n"
            . "TERAPIAS\n\n"
            . "REALIZADAS (" . str_pad($totales['realizadas'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "INASISTENCIAS (" . str_pad($totales['inasistencias'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "NIÑAS (" . str_pad($totales['ninas'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "NIÑOS (" . str_pad($totales['ninos'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "VALORACIONES (" . str_pad($totales['valoraciones'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "PERSONAL (" . str_pad($totales['personal'], 2, '0', STR_PAD_LEFT) . ") Elementos\n\n"
            . "EQUINOS (" . str_pad($totales['equinos'], 2, '0', STR_PAD_LEFT) . ")\n\n"
            . "Asi mismo se realizo actividades de aseo y mantenimiento de toda el area.\n\n"
            . "QUEDANDO PENDIENTES Y A LA ORDEN DEL MANDO SE ANEXAN GRAFICAS.";
    }
}

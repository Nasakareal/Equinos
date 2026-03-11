<?php

namespace App\Http\Controllers;

use App\Models\EquinoterapiaReporte;
use App\Models\EquinoterapiaRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquinoterapiaReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = EquinoterapiaReporte::withCount('registros');

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $reportes = $query->orderBy('fecha', 'desc')->paginate(15)->appends($request->all());

        $inicioSemana = $request->filled('semana_inicio')
            ? Carbon::parse($request->semana_inicio)->startOfWeek()
            : now()->startOfWeek();

        $finSemana = (clone $inicioSemana)->endOfWeek();

        $reportesSemana = EquinoterapiaReporte::with('registros')
            ->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->get();

        $totalesSemana = $this->calcularTotalesColeccion($reportesSemana);

        return view('equinoterapias.index', compact(
            'reportes',
            'inicioSemana',
            'finSemana',
            'totalesSemana'
        ));
    }

    public function create()
    {
        return view('equinoterapias.create');
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

        DB::transaction(function () use ($request, $data) {
            $reporte = EquinoterapiaReporte::create([
                'fecha' => $data['fecha'],
                'valoraciones' => $data['valoraciones'] ?? 0,
                'personal' => $data['personal'] ?? 0,
                'equinos' => $data['equinos'] ?? 0,
                'actividades_area' => $data['actividades_area'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $this->guardarRegistros($reporte, $request);
        });

        return redirect()->route('equinoterapias.index')->with('success', 'Reporte de equinoterapia registrado correctamente.');
    }

    public function show(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->load('registros');

        $totales = $this->calcularTotalesReporte($equinoterapia);
        $mensajeWhatsapp = $this->generarMensajeWhatsapp($equinoterapia, $totales);
        $whatsappUrl = 'https://wa.me/?text=' . urlencode($mensajeWhatsapp);

        $diagnosticos = $equinoterapia->registros
            ->filter(function ($registro) {
                return !empty($registro->diagnostico);
            })
            ->groupBy('diagnostico')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        $inasistencias = $equinoterapia->registros
            ->where('estatus_asistencia', 'INASISTIO')
            ->values();

        return view('equinoterapias.show', compact(
            'equinoterapia',
            'totales',
            'mensajeWhatsapp',
            'whatsappUrl',
            'diagnosticos',
            'inasistencias'
        ));
    }

    public function edit(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->load('registros');

        return view('equinoterapias.edit', compact('equinoterapia'));
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

        return redirect()->route('equinoterapias.show', $equinoterapia)->with('success', 'Reporte de equinoterapia actualizado correctamente.');
    }

    public function destroy(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->delete();

        return redirect()->route('equinoterapias.index')->with('success', 'Reporte de equinoterapia eliminado correctamente.');
    }

    public function whatsapp(EquinoterapiaReporte $equinoterapia)
    {
        $equinoterapia->load('registros');

        $totales = $this->calcularTotalesReporte($equinoterapia);
        $mensajeWhatsapp = $this->generarMensajeWhatsapp($equinoterapia, $totales);
        $whatsappUrl = 'https://wa.me/?text=' . urlencode($mensajeWhatsapp);

        return redirect()->away($whatsappUrl);
    }

    protected function guardarRegistros(EquinoterapiaReporte $reporte, Request $request)
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

    protected function calcularTotalesReporte(EquinoterapiaReporte $reporte)
    {
        $registros = $reporte->registros;

        $realizadas = $registros
            ->where('estatus_asistencia', 'ASISTIO')
            ->where('es_valoracion', false)
            ->count();

        $inasistencias = $registros
            ->where('estatus_asistencia', 'INASISTIO')
            ->count();

        $ninas = $registros
            ->where('estatus_asistencia', 'ASISTIO')
            ->where('sexo', 'NIÑA')
            ->count();

        $ninos = $registros
            ->where('estatus_asistencia', 'ASISTIO')
            ->where('sexo', 'NIÑO')
            ->count();

        $valoraciones = $registros
            ->where('es_valoracion', true)
            ->count();

        return [
            'realizadas' => $realizadas,
            'inasistencias' => $inasistencias,
            'ninas' => $ninas,
            'ninos' => $ninos,
            'valoraciones' => $valoraciones,
            'personal' => (int) $reporte->personal,
            'equinos' => (int) $reporte->equinos,
        ];
    }

    protected function calcularTotalesColeccion($reportes)
    {
        $totales = [
            'realizadas' => 0,
            'inasistencias' => 0,
            'ninas' => 0,
            'ninos' => 0,
            'valoraciones' => 0,
            'personal' => 0,
            'equinos' => 0,
        ];

        foreach ($reportes as $reporte) {
            $actual = $this->calcularTotalesReporte($reporte);

            $totales['realizadas'] += $actual['realizadas'];
            $totales['inasistencias'] += $actual['inasistencias'];
            $totales['ninas'] += $actual['ninas'];
            $totales['ninos'] += $actual['ninos'];
            $totales['valoraciones'] += $actual['valoraciones'];
            $totales['personal'] += $actual['personal'];
            $totales['equinos'] += $actual['equinos'];
        }

        return $totales;
    }

    protected function generarMensajeWhatsapp(EquinoterapiaReporte $reporte, array $totales)
    {
        $fechaTexto = Carbon::parse($reporte->fecha)->format('d/m/Y');

        return "COORDINACIÓN DE AGRUPAMIENTOS\n\n"
            . "AGRUPAMIENTO DE EQUINOS Y CANINOS\n\n"
            . "ÁREA : EQUINOTERAPIA\n\n"
            . "PARA CONOCIMIENTO DEL MANDO GUARDIA CIVIL INFORMA:\n"
            . "Se Dan por concluidas las Equinoterapias por el día de hoy\n"
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
            . "Asi mismo se realizo actividades de aseo y mantenimiento de toda el área.\n\n"
            . "QUEDANDO PENDIENTES Y A LA ORDEN DEL MANDO SE ANEXAN GRAFICAS.";
    }
}

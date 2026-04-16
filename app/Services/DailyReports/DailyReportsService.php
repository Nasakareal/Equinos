<?php

namespace App\Services\DailyReports;

use App\Models\DailyReport;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DailyReportsService
{
    private array $generadores = [];

    public function __construct()
    {
        $this->registrar(new \App\Services\DailyReports\Generators\EstadoFuerzaGenerator());
        $this->registrar(new \App\Services\DailyReports\Generators\ArmamentoEquinosCaninosGenerator());
        $this->registrar(new \App\Services\DailyReports\Generators\ListaPersonalGenerator());
        $this->registrar(new \App\Services\DailyReports\Generators\PaseListaCaninaGenerator());
        $this->registrar(new \App\Services\DailyReports\Generators\PaseListaAgrupamientoEquinosCaninosGenerator());
    }

    public function registrar(DailyReportGenerator $gen): void
    {
        $this->generadores[$gen->tipo()] = $gen;
    }

    public function catalogoTipos(): array
    {
        return collect($this->generadores)
            ->map(fn($g) => [
                'tipo' => $g->tipo(),
                'label' => $g->label(),
                'extension' => $g->extension(),
            ])
            ->values()
            ->all();
    }

    public function estadoArchivos(string $fecha, int $turno_id): array
    {
        $estado = [];

        foreach ($this->generadores as $tipo => $gen) {
            $reporte = DailyReport::query()
                ->where('tipo_reporte', $tipo)
                ->whereDate('fecha', $fecha)
                ->where('turno_id', $turno_id)
                ->latest('id')
                ->first();

            $path = $reporte->archivo ?? $this->pathEsperado($tipo, $fecha, $turno_id, []);

            $estado[$tipo] = [
                'exists' => $path ? Storage::disk('local')->exists($path) : false,
                'path' => $path,
                'name' => $path ? basename($path) : null,
                'daily_report_id' => $reporte->id ?? null,
            ];
        }

        return $estado;
    }

    public function descargar(string $tipo, string $fecha, int $turno_id, array $params = []): Response
    {
        $reporte = DailyReport::query()
            ->where('tipo_reporte', $tipo)
            ->whereDate('fecha', $fecha)
            ->where('turno_id', $turno_id)
            ->latest('id')
            ->first();

        if ($reporte && !empty($reporte->archivo) && Storage::disk('local')->exists($reporte->archivo)) {
            $abs = storage_path('app/' . $reporte->archivo);
            return response()->download($abs, basename($reporte->archivo))->deleteFileAfterSend(false);
        }

        $gen = $this->generador($tipo);
        $path = $gen->generar($fecha, $turno_id, $params);

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'No se pudo generar el reporte.');
        }

        $registro = DailyReport::updateOrCreate(
            [
                'tipo_reporte' => $tipo,
                'fecha' => $fecha,
                'turno_id' => $turno_id,
            ],
            [
                'archivo' => $path,
                'generado_por' => Auth::id(),
            ]
        );

        $abs = storage_path('app/' . $registro->archivo);
        $downloadName = basename($registro->archivo);

        return response()->download($abs, $downloadName)->deleteFileAfterSend(false);
    }

    public function descargarDesdeRegistro(DailyReport $daily_report, string $tipo, array $params = []): Response
    {
        if ($daily_report->tipo_reporte !== $tipo) {
            abort(404, 'El tipo solicitado no coincide con el reporte.');
        }

        $path = $daily_report->archivo;

        if (empty($path) || !Storage::disk('local')->exists($path)) {
            $gen = $this->generador($tipo);
            $path = $gen->generar($daily_report->fecha->format('Y-m-d'), (int) $daily_report->turno_id, $params);

            $daily_report->update([
                'archivo' => $path,
                'generado_por' => $daily_report->generado_por ?: Auth::id(),
            ]);
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'No se encontró el archivo del reporte.');
        }

        $abs = storage_path('app/' . $path);

        return response()->download($abs, basename($path))->deleteFileAfterSend(false);
    }

    public function descargarExcelArmamentoDesdeRegistro(DailyReport $daily_report, array $params = []): Response
    {
        return $this->descargarDesdeRegistro($daily_report, 'armamento_equinos_caninos', $params);
    }

    public function generarYGuardarTodos(string $fecha, int $turno_id, array $params = []): array
    {
        return $this->generarMultiples($fecha, $turno_id, null, $params);
    }

    public function generarMultiples(string $fecha, int $turno_id, ?array $tipos = null, array $params = []): array
    {
        $lista = $tipos ?: array_keys($this->generadores);
        $resultado = [];

        foreach ($lista as $tipo) {
            $tipo = (string) $tipo;
            $gen = $this->generador($tipo);

            $path = $this->pathEsperado($tipo, $fecha, $turno_id, $params);

            if (!Storage::disk('local')->exists($path)) {
                $path = $gen->generar($fecha, $turno_id, $params);
            }

            $reporte = DailyReport::updateOrCreate(
                [
                    'tipo_reporte' => $tipo,
                    'fecha' => $fecha,
                    'turno_id' => $turno_id,
                ],
                [
                    'archivo' => $path,
                    'generado_por' => Auth::id(),
                ]
            );

            $resultado[] = [
                'id' => $reporte->id,
                'tipo' => $tipo,
                'label' => $gen->label(),
                'path' => $path,
                'exists' => Storage::disk('local')->exists($path),
                'name' => basename($path),
            ];
        }

        return $resultado;
    }

    private function generador(string $tipo): DailyReportGenerator
    {
        if (!isset($this->generadores[$tipo])) {
            abort(404, 'Tipo de reporte no soportado: ' . $tipo);
        }

        return $this->generadores[$tipo];
    }

    private function pathEsperado(string $tipo, string $fecha, int $turno_id, array $params): string
    {
        $safeTipo = Str::slug($tipo, '_');
        $ext = $this->generadores[$tipo]->extension() ?: 'xlsx';

        $suffix = '';

        if ($tipo === 'armamento_equinos_caninos' && !empty($params['dependencia'])) {
            $suffix = '_' . Str::slug((string) $params['dependencia'], '_');
        }

        if ($tipo === 'pase_lista_canina') {
            $suffix = '_canina';
        }

        return "daily_reports/{$fecha}/turno_{$turno_id}/{$safeTipo}_{$fecha}_turno_{$turno_id}{$suffix}.{$ext}";
    }
}

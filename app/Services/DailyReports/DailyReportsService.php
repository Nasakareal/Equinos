<?php

namespace App\Services\DailyReports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Services\DailyReports\Contracts\DailyReportGenerator;

class DailyReportsService
{
    private array $generadores = [];

    public function __construct()
    {
        $this->registrar(new \App\Services\DailyReports\Generators\ArmamentoEquinosCaninosGenerator());
        $this->registrar(new \App\Services\DailyReports\Generators\ListaPersonalGenerator());
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
            $path = $this->pathEsperado($tipo, $fecha, $turno_id, []);

            $estado[$tipo] = [
                'exists' => Storage::disk('local')->exists($path),
                'path'   => $path,
                'name'   => basename($path),
            ];
        }

        return $estado;
    }

    public function descargar(string $tipo, string $fecha, int $turno_id, array $params = []): Response
    {
        $gen = $this->generador($tipo);

        $path = $this->pathEsperado($tipo, $fecha, $turno_id, $params);

        if (!Storage::disk('local')->exists($path)) {
            $path = $gen->generar($fecha, $turno_id, $params);
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'No se pudo generar el reporte.');
        }

        $abs = storage_path('app/' . $path);
        $downloadName = basename($path);

        return response()->download($abs, $downloadName)->deleteFileAfterSend(false);
    }

    public function generarMultiples(string $fecha, int $turno_id, ?array $tipos = null, array $params = []): void
    {
        $lista = $tipos ?: array_keys($this->generadores);

        foreach ($lista as $tipo) {
            $tipo = (string) $tipo;
            $gen = $this->generador($tipo);

            $path = $this->pathEsperado($tipo, $fecha, $turno_id, $params);

            if (!Storage::disk('local')->exists($path)) {
                $gen->generar($fecha, $turno_id, $params);
            }
        }
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
        $ext = $this->generadores[$tipo]->extension() ?? 'xlsx';

        $suffix = '';
        if ($tipo === 'armamento_equinos_caninos' && !empty($params['dependencia'])) {
            $suffix = '_' . Str::slug((string) $params['dependencia'], '_');
        }

        return "daily_reports/{$fecha}/turno_{$turno_id}/{$safeTipo}_{$fecha}_turno_{$turno_id}{$suffix}.{$ext}";
    }
}

<?php

namespace App\Services\DailyReports\Generators;

use Illuminate\Support\Facades\Storage;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EstadoFuerzaGenerator implements DailyReportGenerator
{
    public function tipo(): string
    {
        return 'estado_fuerza';
    }

    public function label(): string
    {
        return 'Estado de Fuerza Diario (Excel)';
    }

    public function extension(): string
    {
        return 'xlsx';
    }

    public function generar(string $fecha, int $turno_id, array $params = []): string
    {
        $path = "daily_reports/{$fecha}/turno_{$turno_id}/estado_fuerza_{$fecha}_turno_{$turno_id}.xlsx";

        Storage::disk('local')->makeDirectory("daily_reports/{$fecha}/turno_{$turno_id}");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ESTADO DE FUERZA');
        $sheet->setCellValue('A1', 'ESTADO DE FUERZA');
        $sheet->setCellValue('A2', 'Fecha: ' . $fecha);
        $sheet->setCellValue('A3', 'Turno ID: ' . $turno_id);

        $tmp = storage_path('app/tmp_estado_fuerza_' . uniqid() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmp);

        Storage::disk('local')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $path;
    }
}

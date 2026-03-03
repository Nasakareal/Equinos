<?php

namespace App\Services\DailyReports\Generators;

use Illuminate\Support\Facades\Storage;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ListaPersonalGenerator implements DailyReportGenerator
{
    public function tipo(): string
    {
        return 'lista_personal';
    }

    public function label(): string
    {
        return 'Lista de Personal (Excel)';
    }

    public function extension(): string
    {
        return 'xlsx';
    }

    public function generar(string $fecha, int $turno_id, array $params = []): string
    {
        $path = "daily_reports/{$fecha}/turno_{$turno_id}/lista_personal_{$fecha}_turno_{$turno_id}.xlsx";
        Storage::disk('local')->makeDirectory("daily_reports/{$fecha}/turno_{$turno_id}");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LISTA PERSONAL');
        $sheet->setCellValue('A1', 'LISTA DE PERSONAL');
        $sheet->setCellValue('A2', 'Fecha: ' . $fecha);
        $sheet->setCellValue('A3', 'Turno ID: ' . $turno_id);

        $sheet->setCellValue('A5', 'NOMBRE');
        $sheet->setCellValue('B5', 'ADSCRIPCIÓN');
        $sheet->setCellValue('C5', 'ESTATUS');

        $tmp = storage_path('app/tmp_lista_personal_' . uniqid() . '.xlsx');
        (new Xlsx($spreadsheet))->save($tmp);

        Storage::disk('local')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $path;
    }
}

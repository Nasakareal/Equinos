<?php

namespace App\Services\DailyReports\Generators;

use App\Models\Personal;
use App\Models\Turno;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use App\Services\TurnoActual;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
        $tz = 'America/Mexico_City';
        $fechaCarbon = Carbon::parse($fecha, $tz)->locale('es');
        $fechaTexto = mb_strtoupper($fechaCarbon->translatedFormat('d \\D\\E F \\D\\E Y'));
        $fechaArchivo = $fechaCarbon->format('d-m-Y');

        $turno = Turno::query()->find($turno_id);
        $turnoClave = mb_strtoupper(trim((string)($turno->clave ?? '')));
        $turnoNombre = mb_strtoupper(trim((string)($turno->nombre ?? '')));

        $areaId = isset($params['area_id']) && $params['area_id'] !== null
            ? (int)$params['area_id']
            : null;

        $areaNombre = trim((string)($params['area_nombre'] ?? ''));
        if ($areaNombre === '') {
            $areaNombre = 'TODAS LAS ÁREAS';
        }

        $requeridosIds = TurnoActual::requeridosIds($areaId);

        $personal = empty($requeridosIds)
            ? collect()
            : Personal::query()
                ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
                ->leftJoin('turnos', 'personals.turno_id', '=', 'turnos.id')
                ->whereIn('personals.id', $requeridosIds)
                ->select(
                    'personals.*',
                    'areas.nombre as area_nombre',
                    'turnos.nombre as turno_nombre',
                    'turnos.clave as turno_clave'
                )
                ->orderBy('areas.nombre')
                ->orderBy('personals.grado')
                ->orderBy('personals.nombres')
                ->get();

        $totalPersonal = $personal->count();
        $totalResponsables = $personal->where('es_responsable', 1)->count();
        $totalSiempreVisibles = $personal->where('siempre_visible', 1)->count();
        $totalActivos = $personal->where('activo', 1)->count();

        $porArea = $personal
            ->groupBy(function ($item) {
                return $item->area_nombre ?: 'Sin área';
            })
            ->map(fn ($items) => $items->count())
            ->sortKeys();

        $path = "daily_reports/{$fecha}/turno_{$turno_id}/{$fechaArchivo}_ESTADO_DE_FUERZA_TURNO_{$turnoClave}.xlsx";

        Storage::disk('local')->makeDirectory("daily_reports/{$fecha}/turno_{$turno_id}");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ESTADO DE FUERZA');

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(45);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(40);

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'ESTADO DE FUERZA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', $fechaTexto);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'TURNO: ' . ($turnoNombre !== '' ? $turnoNombre : 'NO DEFINIDO') . ($turnoClave !== '' ? ' (' . $turnoClave . ')' : ''));
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'ÁREA: ' . mb_strtoupper($areaNombre));
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A6', 'TOTAL PERSONAL');
        $sheet->setCellValue('B6', $totalPersonal);
        $sheet->setCellValue('C6', 'RESPONSABLES');
        $sheet->setCellValue('D6', $totalResponsables);
        $sheet->setCellValue('E6', 'SIEMPRE VISIBLES');
        $sheet->setCellValue('F6', $totalSiempreVisibles);

        $sheet->setCellValue('A7', 'ACTIVOS');
        $sheet->setCellValue('B7', $totalActivos);

        $sheet->getStyle('A6:F7')->getFont()->setBold(true);
        $sheet->getStyle('A6:F7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A6:F7')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A6:F7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('D9EAD3');

        $row = 9;

        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->setCellValue("A{$row}", 'RESUMEN POR ÁREA');
        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('BDD7EE');
        $row++;

        $sheet->setCellValue("A{$row}", 'ÁREA');
        $sheet->setCellValue("B{$row}", 'TOTAL');
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:B{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('E2F0D9');
        $resumenInicio = $row;
        $row++;

        foreach ($porArea as $nombreArea => $total) {
            $sheet->setCellValue("A{$row}", $nombreArea);
            $sheet->setCellValue("B{$row}", $total);
            $row++;
        }

        if ($porArea->isEmpty()) {
            $sheet->setCellValue("A{$row}", 'SIN DATOS');
            $sheet->setCellValue("B{$row}", 0);
            $row++;
        }

        $resumenFin = $row - 1;

        $row++;

        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->setCellValue("A{$row}", 'DETALLE DE PERSONAL');
        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('BDD7EE');
        $row++;

        $sheet->setCellValue("A{$row}", 'NO.');
        $sheet->setCellValue("B{$row}", 'GRADO');
        $sheet->setCellValue("C{$row}", 'NOMBRE');
        $sheet->setCellValue("D{$row}", 'ÁREA');
        $sheet->setCellValue("E{$row}", 'TURNO');
        $sheet->setCellValue("F{$row}", 'RESPONSABLE');
        $sheet->setCellValue("G{$row}", 'OBSERVACIONES');

        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('D9EAD3');

        $detalleInicio = $row;
        $row++;
        $contador = 1;

        foreach ($personal as $item) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", mb_strtoupper((string)($item->grado ?? '')));
            $sheet->setCellValue("C{$row}", mb_strtoupper((string)($item->nombres ?? '')));
            $sheet->setCellValue("D{$row}", mb_strtoupper((string)($item->area_nombre ?? 'SIN ÁREA')));
            $sheet->setCellValue("E{$row}", mb_strtoupper((string)($item->turno_clave ?? $item->turno_nombre ?? '')));
            $sheet->setCellValue("F{$row}", (int)($item->es_responsable ?? 0) === 1 ? 'SÍ' : 'NO');
            $sheet->setCellValue("G{$row}", mb_strtoupper((string)($item->observaciones ?? '')));

            $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        if ($personal->isEmpty()) {
            $sheet->mergeCells("A{$row}:G{$row}");
            $sheet->setCellValue("A{$row}", 'NO HAY PERSONAL PARA MOSTRAR');
            $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        $detalleFin = $row - 1;

        $sheet->getStyle("A{$resumenInicio}:B{$resumenFin}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$detalleInicio}:G{$detalleFin}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        foreach (range(1, $detalleFin) as $r) {
            $sheet->getRowDimension($r)->setRowHeight(22);
        }

        $tmp = storage_path('app/tmp_estado_fuerza_' . uniqid() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmp);

        Storage::disk('local')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $path;
    }
}

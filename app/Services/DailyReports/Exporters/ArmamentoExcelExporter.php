<?php

namespace App\Services\DailyReports\Exporters;

use App\Models\DailyReport;
use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArmamentoExcelExporter
{
    public function download(DailyReport $daily_report, ?string $dependencia)
    {
        if (empty($dependencia)) {
            return back()->with('error', 'Falta la dependencia. Envia ?dependencia=...');
        }

        $daily_report->load('turno');

        $fecha = Carbon::parse($daily_report->fecha, 'America/Mexico_City');
        $fecha_titulo = $fecha->format('d-m-Y');
        $fecha_texto = mb_strtoupper($fecha->translatedFormat('d \\D\\E F \\D\\E Y'));

        $turno_clave = (string)($daily_report->turno?->clave ?? '');
        $dep_archivo = trim(str_ireplace('AGRUPAMIENTO DE ', '', $dependencia));

        $filename = $fecha_titulo . ' ARMAMENTO ' . mb_strtoupper($dep_archivo) . ' TURNO ' . mb_strtoupper($turno_clave) . '.xlsx';

        $personals = Personal::query()
            ->where('activo', 1)
            ->where('dependencia', $dependencia)
            ->orderByDesc('es_responsable')
            ->orderBy('grado')
            ->orderBy('nombres')
            ->get();

        if ($personals->isEmpty()) {
            return back()->with('error', 'No hay personal activo para esa dependencia.');
        }

        $turnoPorPersonal = ServiceSchedule::query()
            ->where('activo', 1)
            ->where('tipo', 'CICLICO')
            ->whereIn('personal_id', $personals->pluck('id'))
            ->select(['personal_id', 'turno_id'])
            ->get()
            ->keyBy('personal_id');

        $turnos = Turno::query()
            ->where('activo', 1)
            ->orderBy('id')
            ->get()
            ->keyBy('id');

        $encargados = $personals->where('es_responsable', 1)->values();
        $grupoA = collect();
        $grupoB = collect();
        $grupoM = collect();

        foreach ($personals->where('es_responsable', 0) as $p) {
            $turno_id = $turnoPorPersonal->get($p->id)->turno_id ?? null;
            $clave = $turno_id ? ($turnos->get($turno_id)->clave ?? null) : null;

            if ($clave === 'A') $grupoA->push($p);
            elseif ($clave === 'B') $grupoB->push($p);
            elseif ($clave === 'MIXTO') $grupoM->push($p);
            else $grupoB->push($p);
        }

        $turno_activo = strtoupper(trim($turno_clave));
        $forzar_franco_A = ($turno_activo === 'B');
        $forzar_franco_B = ($turno_activo === 'A');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ARMAMENTO');

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(42);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);

        $logoPath = public_path('img/guardiacivil.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(65);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', mb_strtoupper($dependencia));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'LISTADO  DE PERSONAL CON ARMAMENTO');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3', $fecha_texto);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $startRow = 5;
        $sheet->fromArray(['No.','GRADO','NOMBRE','ENTRADA','SALIDA','HORARIO'], null, "A{$startRow}");
        $this->styleHeaderRow($sheet, $startRow);

        $row = $startRow + 1;
        $contador = 1;

        foreach ($encargados as $enc) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", $enc->grado);
            $sheet->setCellValue("C{$row}", $enc->nombres);
            $sheet->setCellValue("F{$row}", 'DISPONIBLE 24 HORAS');
            $this->styleDataRow($sheet, $row);
            $row++;
        }

        $row = $this->printTurnSection($sheet, $row, 'PERSONAL TURNO A', $grupoA, $contador, false, $forzar_franco_A);
        $contador += $grupoA->count();

        $row = $this->printTurnSection($sheet, $row, 'PERSONAL TURNO B', $grupoB, $contador, false, $forzar_franco_B);
        $contador += $grupoB->count();

        $row = $this->printTurnSection($sheet, $row, 'PERSONAL TURNO MIXTO', $grupoM, $contador, true, false);
        $contador += $grupoM->count();

        $row += 2;
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", 'R E S P E T U O S A M E N T E');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 3;

        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", 'ENCARGADO DEL AGRUPAMIENTO DE EQUINOS Y CANINOS');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $nombreEncargado = $encargados->first()?->nombres ?? '';
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", $nombreEncargado ? ('CMTE. ' . $nombreEncargado) : '');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        /* =========================
           BORDES GENERALES
        ========================== */
        $sheet->getStyle("A{$startRow}:F{$row}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        /* =========================
           FILAS SIN BORDES (43–46)
        ========================== */
        foreach ([43, 44, 45, 46] as $r) {
            $sheet->getStyle("A{$r}:F{$r}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_NONE);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /* ======== helpers intactos ======== */

    private function styleHeaderRow($sheet, int $row): void
    {
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:F{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
    }

    private function styleSectionTitle($sheet, int $row): void
    {
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('B4C6E7');
        $sheet->getStyle("A{$row}:F{$row}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function styleDataRow($sheet, int $row): void
    {
        $sheet->getStyle("A{$row}:F{$row}")
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$row}:B{$row}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$row}:F{$row}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function getEntradaSalidaDesdeObservaciones(?string $obs): array
    {
        $up = mb_strtoupper((string)$obs);

        if (str_contains($up, 'VACACIONES')) return ['VACACIONES', 'VACACIONES'];
        if (str_contains($up, 'FRANCO')) return ['FRANCO', 'FRANCO'];
        if (str_contains($up, 'PROCESO ADMINISTRATIVO')) return ['PROCESO ADMINISTRATIVO', 'PROCESO ADMINISTRATIVO'];

        return ['', ''];
    }

    private function printTurnSection($sheet, int $row, string $titulo, $personals, int $contadorInicio, bool $horarioVacio = false, bool $forzarFranco = false): int
    {
        $sheet->setCellValue("A{$row}", $titulo);
        $this->styleSectionTitle($sheet, $row);
        $row++;

        $contador = $contadorInicio;

        foreach ($personals as $p) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", $p->grado);
            $sheet->setCellValue("C{$row}", $p->nombres);

            [$entrada, $salida] = $this->getEntradaSalidaDesdeObservaciones($p->observaciones);

            if ($forzarFranco && trim($entrada) === '' && trim($salida) === '') {
                $entrada = 'FRANCO';
                $salida = 'FRANCO';
            }

            $sheet->setCellValue("D{$row}", $entrada);
            $sheet->setCellValue("E{$row}", $salida);
            $sheet->setCellValue("F{$row}", $horarioVacio ? '' : '24X24');

            $this->styleDataRow($sheet, $row);
            $row++;
        }

        return $row + 1;
    }
}

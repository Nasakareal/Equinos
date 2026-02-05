<?php

namespace App\Services\DailyReports\Exporters;

use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ListaPersonalExcelExporter
{
    // Orden preferido de turnos
    private const TURNOS_ORDEN = [
        'TURNO A',
        'A',
        'TURNO B',
        'B',
        'MIXTO',
        'J.A',
        'JORNADA ACUMULADA',
        'VESPERTINO',
        'MATUTINO',
    ];

    public function download(DailyReport $daily_report)
    {
        // 1) Unir reportes del mismo día (A/B/MIXTO) para sacar 1 solo Excel completo
        $fecha = Carbon::parse($daily_report->fecha, 'America/Mexico_City')->toDateString();
        $tipo  = (string)$daily_report->tipo_reporte;

        $reportes_del_dia = DailyReport::query()
            ->whereDate('fecha', $fecha)
            ->where('tipo_reporte', $tipo)
            ->with([
                'turno',
                'rows' => function ($q) {
                    $q->orderBy('dependencia')
                      ->orderBy('orden')
                      ->orderBy('id');
                }
            ])
            ->get();

        // Si por alguna razón solo existe uno, seguimos con ese
        if ($reportes_del_dia->isEmpty()) {
            $reportes_del_dia = collect([$daily_report->load(['turno', 'rows'])]);
        }

        // Normalizamos filas: metemos "turno" en cada row desde su DailyReport padre
        $allRows = $this->flattenRowsConTurno($reportes_del_dia);

        $fecha_obj = Carbon::parse($fecha, 'America/Mexico_City');
        $fecha_ddmmyyyy = $fecha_obj->format('d-m-Y');
        $nombre_archivo = $fecha_ddmmyyyy . ' LISTA DE PERSONAL.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LISTA DE PERSONAL');

        // Config general
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        $sheet->getColumnDimension('A')->setWidth(5);   // No.
        $sheet->getColumnDimension('B')->setWidth(14);  // Grado
        $sheet->getColumnDimension('C')->setWidth(34);  // Nombres
        $sheet->getColumnDimension('D')->setWidth(22);  // Dependencia
        $sheet->getColumnDimension('E')->setWidth(16);  // Arma corta
        $sheet->getColumnDimension('F')->setWidth(14);  // Matricula corta
        $sheet->getColumnDimension('G')->setWidth(16);  // Arma larga
        $sheet->getColumnDimension('H')->setWidth(14);  // Matricula larga
        $sheet->getColumnDimension('I')->setWidth(10);  // Turno
        $sheet->getColumnDimension('J')->setWidth(12);  // Hora entrada
        $sheet->getColumnDimension('K')->setWidth(16);  // Firma entrada
        $sheet->getColumnDimension('L')->setWidth(12);  // Hora salida
        $sheet->getColumnDimension('M')->setWidth(16);  // Firma salida
        $sheet->getColumnDimension('N')->setWidth(22);  // Despliegue/Servicio
        $sheet->getColumnDimension('O')->setWidth(28);  // Observaciones

        // Logo
        $logo_path = public_path('img/imagen1.png');
        if (is_file($logo_path)) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath($logo_path);
            $drawing->setHeight(70);
            $drawing->setCoordinates('E1');
            $drawing->setWorksheet($sheet);
        }

        // Encabezado del oficio (usa el turno del primer reporte, pero no te afecta porque en filas viene el real)
        $turnoNombrePortada = (string)($reportes_del_dia->first()?->turno?->nombre ?? '');
        $fila = 1;
        $fila = $this->printOficioHeader($sheet, $fecha_obj, $turnoNombrePortada, $fila);
        $fila = $this->printTableHeaders($sheet, $fila);

        // 2) Agrupar por dependencia (como tus ejemplos)
        $rows_por_dependencia = $allRows
            ->groupBy(fn($r) => mb_strtoupper(trim((string)($r->dependencia ?? ''))));

        // Orden deseado de dependencias (primero el agrupamiento general)
        $orden_deps = [
            'AGRUPAMIENTO DE EQUINOS Y CANINOS',
            'AREA CANINA',
            'EQUINOTERAPIA',
            'OFICINA',
        ];

        // Re-ordenamos dependencias: primero las conocidas, luego el resto
        $keys = $rows_por_dependencia->keys()->all();
        usort($keys, function ($a, $b) use ($orden_deps) {
            $ia = array_search($a, $orden_deps, true);
            $ib = array_search($b, $orden_deps, true);
            $ia = ($ia === false) ? 999 : $ia;
            $ib = ($ib === false) ? 999 : $ib;
            if ($ia !== $ib) return $ia <=> $ib;
            return strcmp((string)$a, (string)$b);
        });

        $contadorGlobal = 1;

        foreach ($keys as $dep_up) {
            $items_dep = $rows_por_dependencia->get($dep_up, collect());

            // Página nueva antes de AREA CANINA (como tu ejemplo “bueno”)
            if ($dep_up === 'AREA CANINA') {
                $sheet->setBreak("A{$fila}", Worksheet::BREAK_ROW);
                $fila += 1;

                // reinicia contador dentro de AREA CANINA (así lo tienes en tu muestra)
                $contadorGlobal = 1;

                $fila = $this->printOficioHeader($sheet, $fecha_obj, $turnoNombrePortada, $fila);

                // Banda “AREA CANINA”
                $sheet->mergeCells("A{$fila}:O{$fila}");
                $sheet->setCellValue("A{$fila}", 'AREA CANINA');
                $sheet->getStyle("A{$fila}:O{$fila}")->getFont()->setBold(true);
                $sheet->getStyle("A{$fila}:O{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $fila += 1;

                $fila = $this->printTableHeaders($sheet, $fila);
            }

            // Banda de dependencia
            $this->printBand($sheet, $fila, $dep_up !== '' ? $dep_up : 'SIN DEPENDENCIA');
            $fila++;

            // 3) Dentro de la dependencia, imprimimos por turnos (A, B, MIXTO)
            $items_por_turno = $items_dep->groupBy(function ($r) {
                $t = mb_strtoupper(trim((string)($r->__turno ?? '')));
                return $t !== '' ? $t : 'SIN TURNO';
            });

            $turno_keys = $items_por_turno->keys()->all();
            usort($turno_keys, fn($a, $b) => $this->turnoWeight($a) <=> $this->turnoWeight($b));

            foreach ($turno_keys as $tk) {
                $items_turno = $items_por_turno->get($tk, collect());

                // Si quieres que aparezca literal como “TURNO A”, “TURNO B” etc:
                if (in_array($tk, ['A', 'B'], true)) {
                    $titulo_turno = $tk === 'A' ? 'TURNO A' : 'TURNO B';
                } else {
                    $titulo_turno = $tk;
                }

                // Fila “TURNO X”
                $sheet->mergeCells("A{$fila}:O{$fila}");
                $sheet->setCellValue("A{$fila}", $titulo_turno);
                $sheet->getStyle("A{$fila}:O{$fila}")->getFont()->setBold(true);
                $sheet->getStyle("A{$fila}:O{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("A{$fila}:O{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $fila++;

                // Orden especial: SEGUNDO primero en AREA CANINA
                if ($dep_up === 'AREA CANINA') {
                    $items_turno = $items_turno->sortByDesc(function ($r) {
                        $nombre = mb_strtoupper(trim((string)($r->nombre ?? '')));
                        return $nombre === 'SEGUNDO GONZALEZ RODRIGO' ? 1 : 0;
                    })->values();
                }

                // Orden especial: Fredy arriba en Agrupamiento general (si quieres)
                if ($dep_up === 'AGRUPAMIENTO DE EQUINOS Y CANINOS') {
                    $items_turno = $items_turno->sortByDesc(function ($r) {
                        $nombre = mb_strtoupper(trim((string)($r->nombre ?? '')));
                        return $nombre === 'GONZALEZ OROZCO FREDY ERASTO' ? 1 : 0;
                    })->values();
                }

                foreach ($items_turno as $r) {
                    $sheet->setCellValue("A{$fila}", $contadorGlobal++);
                    $sheet->setCellValue("B{$fila}", (string)($r->grado ?? ''));
                    $sheet->setCellValue("C{$fila}", (string)($r->nombre ?? ''));
                    $sheet->setCellValue("D{$fila}", (string)($r->dependencia ?? ''));

                    $sheet->setCellValue("E{$fila}", (string)($r->arma_corta ?? ''));
                    $sheet->setCellValue("F{$fila}", (string)($r->matricula_corta ?? ''));
                    $sheet->setCellValue("G{$fila}", (string)($r->arma_larga ?? ''));
                    $sheet->setCellValue("H{$fila}", (string)($r->matricula_larga ?? ''));

                    $sheet->setCellValue("I{$fila}", (string)($r->__turno ?? ''));

                    $sheet->setCellValue("J{$fila}", (string)($r->hora_entrada ?? ''));
                    $sheet->setCellValue("K{$fila}", (string)($r->firma_entrada ?? ''));
                    $sheet->setCellValue("L{$fila}", (string)($r->hora_salida ?? ''));
                    $sheet->setCellValue("M{$fila}", (string)($r->firma_salida ?? ''));

                    // Aquí es donde debe venir FRANCO desde la BD (controller)
                    $sheet->setCellValue("N{$fila}", (string)($r->despliegue_servicio ?? ''));
                    $sheet->setCellValue("O{$fila}", (string)($r->observaciones ?? ''));

                    // Bordes finos + alineación
                    $sheet->getStyle("A{$fila}:O{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle("A{$fila}:O{$fila}")->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle("C{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("O{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->getStyle("C{$fila}:C{$fila}")->getAlignment()->setWrapText(true);
                    $sheet->getStyle("N{$fila}:O{$fila}")->getAlignment()->setWrapText(true);

                    $fila++;
                }
            }
        }

        // Pie (firmas) al final
        $fila += 2;

        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue("A{$fila}", "R E S P E T U O S A M E N T E");
        $sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$fila}")->getFont()->setBold(true);

        $fila++;

        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue("A{$fila}", "ENCARGADO DEL AGRUPAMIENTO DE EQUINOS Y CANINOS");
        $sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $fila += 2;

        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue("A{$fila}", "CMTE. FREDY ERASTO GONZALEZ OROZCO.");
        $sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$fila}")->getFont()->setBold(true);

        // Página
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombre_archivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function flattenRowsConTurno(Collection $reportes_del_dia): Collection
    {
        return $reportes_del_dia->flatMap(function ($rep) {
            $turno = mb_strtoupper(trim((string)($rep->turno?->nombre ?? '')));
            return $rep->rows->map(function ($r) use ($turno) {
                // “colgamos” el turno sin tocar tu BD
                $r->__turno = $turno !== '' ? $turno : '';
                return $r;
            });
        })->values();
    }

    private function turnoWeight(string $turno): int
    {
        $t = mb_strtoupper(trim($turno));
        foreach (self::TURNOS_ORDEN as $i => $k) {
            if ($t === mb_strtoupper($k)) return $i;
        }
        return 999;
    }

    private function printBand($sheet, int $fila, string $texto): void
    {
        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue("A{$fila}", $texto);
        $sheet->getStyle("A{$fila}:O{$fila}")->getFont()->setBold(true);
        $sheet->getStyle("A{$fila}:O{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$fila}:O{$fila}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');
        $sheet->getStyle("A{$fila}:O{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    private function printOficioHeader($sheet, Carbon $fecha, string $turnoNombre, int $filaInicio): int
    {
        $sheet->mergeCells("A{$filaInicio}:D" . ($filaInicio + 2));
        $sheet->setCellValue(
            "A{$filaInicio}",
            "MTRO. CARLOS ROBERTO GOMEZ RUIZ\nENCARGADO DE DESPACHO DE LA\nCOORDINACION DE AGRUPAMIENTOS\nPRESENTE."
        );
        $sheet->getStyle("A{$filaInicio}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

        $fila = $filaInicio + 4;

        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue(
            "A{$fila}",
            'REMITO A USTED FATIGA DEL PERSONAL DE LAS 08:00 HORAS DEL DIA ' .
            $fecha->translatedFormat('d \\D\\E F \\D\\E\\L Y') .
            ' A LAS 08:00 HORAS DEL DIA ' .
            $fecha->copy()->addDay()->translatedFormat('d \\D\\E F \\D\\E\\L Y') . '.'
        );
        $sheet->getStyle("A{$fila}")->getAlignment()->setWrapText(true);

        $fila++;

        $sheet->mergeCells("A{$fila}:O{$fila}");
        $sheet->setCellValue("A{$fila}", 'AGRUPAMIENTO DE EQUINOS Y CANINOS TURNO "' . ($turnoNombre ?? '') . '"');
        $sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$fila}")->getFont()->setBold(true);

        return $fila + 2;
    }

    private function printTableHeaders($sheet, int $fila): int
    {
        $sheet->setCellValue("A{$fila}", 'No.');
        $sheet->setCellValue("B{$fila}", 'GRADO');
        $sheet->setCellValue("C{$fila}", 'NOMBRES');
        $sheet->setCellValue("D{$fila}", 'DEPENDENCIA');

        $sheet->mergeCells("E{$fila}:H{$fila}");
        $sheet->setCellValue("E{$fila}", 'ARMAMENTO');

        $sheet->setCellValue("I{$fila}", 'TURNO');

        $sheet->mergeCells("J{$fila}:M{$fila}");
        $sheet->setCellValue("J{$fila}", 'FIRMAS');

        $sheet->setCellValue("N{$fila}", 'DESPLIEGUE/SERVICIO');
        $sheet->setCellValue("O{$fila}", 'OBSERVACIONES');

        $fila++;

        $sheet->setCellValue("E{$fila}", 'ARMA CORTA');
        $sheet->setCellValue("F{$fila}", 'MATRICULA');
        $sheet->setCellValue("G{$fila}", 'ARMA LARGA');
        $sheet->setCellValue("H{$fila}", 'MATRICULA');

        $sheet->setCellValue("J{$fila}", 'HORA ENTRADA');
        $sheet->setCellValue("K{$fila}", 'FIRMA DE ENTRADA');
        $sheet->setCellValue("L{$fila}", 'HORA SALIDA');
        $sheet->setCellValue("M{$fila}", 'FIRMA DE SALIDA');

        $sheet->getStyle("A" . ($fila - 1) . ":O{$fila}")->getFont()->setBold(true);

        $sheet->getStyle("A" . ($fila - 1) . ":O{$fila}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle("A" . ($fila - 1) . ":O{$fila}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAD3');

        $sheet->getStyle("A" . ($fila - 1) . ":O{$fila}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        return $fila + 1;
    }
}

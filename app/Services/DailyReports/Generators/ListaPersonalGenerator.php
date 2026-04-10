<?php

namespace App\Services\DailyReports\Generators;

use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $area = trim((string)($params['area'] ?? ''));
        $area = mb_strtoupper($area, 'UTF-8');

        $areasObjetivo = $area !== '' ? [$area] : ['EQUINOS', 'CANINOS'];
        $tituloArea = count($areasObjetivo) === 1 ? $areasObjetivo[0] : 'EQUINOS Y CANINOS';

        $tz = 'America/Mexico_City';
        $fechaC = Carbon::parse($fecha, $tz)->locale('es');

        $fechaArchivo = $fechaC->format('d-m-Y');
        $fechaInicioTexto = mb_strtoupper($fechaC->translatedFormat('d \\D\\E F \\D\\E Y'), 'UTF-8');

        $fechaFin = $fechaC->copy()->addDay();
        $fechaFinTexto = mb_strtoupper($fechaFin->translatedFormat('d \\D\\E F \\D\\E Y'), 'UTF-8');

        $turno = Turno::query()->find($turno_id);
        $turnoClave = mb_strtoupper(trim((string)($turno?->clave ?? '')), 'UTF-8');

        $filename = $fechaArchivo . ' LISTA DE PERSONAL ' . $tituloArea . ' TURNO ' . $turnoClave . '.xlsx';

        $personals = Personal::query()
            ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
            ->where('personals.activo', 1)
            ->whereIn(DB::raw('TRIM(UPPER(areas.nombre))'), $areasObjetivo)
            ->whereHas('asignacionesArmamento', function ($q) {
                $q->whereNull('fecha_devolucion')
                    ->where('status', 'ASIGNADA')
                    ->whereHas('weapon', function ($w) {
                        $w->where('estado', 'ACTIVA');
                    });
            })
            ->with([
                'asignacionesArmamento' => function ($q) {
                    $q->whereNull('fecha_devolucion')
                        ->where('status', 'ASIGNADA')
                        ->latest('id');
                },
                'asignacionesArmamento.weapon',
            ])
            ->select('personals.*')
            ->orderBy('personals.grado')
            ->orderBy('personals.nombres')
            ->get();

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

        $esSiemprePrimero = function (Personal $p): bool {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''), 'UTF-8');
            $nombre = mb_strtoupper((string)($p->nombres ?? ''), 'UTF-8');

            return str_contains($cargo, 'SUBDIRECTOR')
                || str_contains($cargo, 'CMTE. FREDY ERASTO')
                || str_contains($nombre, 'FREDY ERASTO GONZALEZ OROZCO');
        };

        $siemprePrimero = $personals->filter(fn ($p) => $esSiemprePrimero($p))->values();
        $resto = $personals->reject(fn ($p) => $esSiemprePrimero($p))->values();

        $grupoA = collect();
        $grupoB = collect();
        $grupoM = collect();

        foreach ($resto as $p) {
            $tId = $turnoPorPersonal->get($p->id)->turno_id ?? null;
            $clave = $tId ? ($turnos->get($tId)->clave ?? null) : null;
            $clave = mb_strtoupper(trim((string)$clave), 'UTF-8');

            if ($clave === 'A') {
                $grupoA->push($p);
            } elseif ($clave === 'B') {
                $grupoB->push($p);
            } elseif ($clave === 'MIXTO') {
                $grupoM->push($p);
            } else {
                $grupoB->push($p);
            }
        }

        $grupoA = $this->ponerEncargadosDeTurnoAlInicio($grupoA, 'A');
        $grupoB = $this->ponerEncargadosDeTurnoAlInicio($grupoB, 'B');
        $grupoM = $this->ponerEncargadosDeTurnoAlInicio($grupoM, 'MIXTO');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LISTA PERSONAL');

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(8);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(38);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);

        $logoPath = public_path('img/guardiacivil.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(60);
            $drawing->setCoordinates('B2');
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $sheet->mergeCells('B9:G9');
        $sheet->setCellValue('B9', 'MTRO. CARLOS ROBERTO GOMEZ RUIZ');
        $sheet->getStyle('B9')->getFont()->setBold(true)->setSize(11);

        $sheet->mergeCells('B10:G10');
        $sheet->setCellValue('B10', 'ENCARGADO DE DESPACHO DE LA');
        $sheet->getStyle('B10')->getFont()->setBold(true)->setSize(11);

        $sheet->mergeCells('B11:G11');
        $sheet->setCellValue('B11', 'COORDINACION DE AGRUPAMIENTOS');
        $sheet->getStyle('B11')->getFont()->setBold(true)->setSize(11);

        $sheet->mergeCells('B12:G12');
        $sheet->setCellValue('B12', 'PRESENTE.');
        $sheet->getStyle('B12')->getFont()->setBold(true)->setSize(11);

        $sheet->mergeCells('B14:G14');
        $sheet->setCellValue(
            'B14',
            'REMITO A USTED FATIGA DEL PERSONAL DE LAS 08:00 HORAS DEL DIA ' .
            $fechaInicioTexto .
            ' A LAS 08:00 HORAS DEL DIA ' .
            $fechaFinTexto .
            '.'
        );
        $sheet->getStyle('B14:G14')->getAlignment()->setWrapText(true)->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getRowDimension(14)->setRowHeight(36);

        $sheet->mergeCells('B16:G16');
        $sheet->setCellValue('B16', 'AGRUPAMIENTO DE ' . $tituloArea . ' TURNO "' . $turnoClave . '"');
        $sheet->getStyle('B16')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('B16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B17', 'No.');
        $sheet->setCellValue('C17', 'GRADO');
        $sheet->setCellValue('D17', 'NOMBRES');
        $sheet->setCellValue('E17', 'ÁREA');
        $sheet->setCellValue('F17', 'ARMAMENTO');

        $sheet->mergeCells('F17:G17');
        $sheet->setCellValue('F18', 'ARMA CORTA');
        $sheet->setCellValue('G18', 'MATRICULA');

        $this->styleHeaderTop($sheet, 17);
        $this->styleHeaderBottom($sheet, 18);

        $row = 19;
        $contador = 1;

        foreach ($siemprePrimero as $p) {
            $arma = $this->obtenerArmamento($p);

            $sheet->setCellValue("B{$row}", $contador++);
            $sheet->setCellValue("C{$row}", $p->grado);
            $sheet->setCellValue("D{$row}", $p->nombres);
            $sheet->setCellValue("E{$row}", 'E');
            $sheet->setCellValue("F{$row}", $arma['arma']);
            $sheet->setCellValue("G{$row}", $arma['matricula']);
            $this->styleDataRow($sheet, $row);
            $row++;
        }

        if ($grupoA->isNotEmpty()) {
            $sheet->setCellValue("B{$row}", 'TURNO A');
            $this->styleSectionTitle($sheet, $row, 'B', 'G');
            $row++;

            foreach ($grupoA as $p) {
                $arma = $this->obtenerArmamento($p);

                $sheet->setCellValue("B{$row}", $contador++);
                $sheet->setCellValue("C{$row}", $p->grado);
                $sheet->setCellValue("D{$row}", $p->nombres);
                $sheet->setCellValue("E{$row}", 'E');
                $sheet->setCellValue("F{$row}", $arma['arma']);
                $sheet->setCellValue("G{$row}", $arma['matricula']);
                $this->styleDataRow($sheet, $row);
                $row++;
            }
        }

        if ($grupoB->isNotEmpty()) {
            $sheet->setCellValue("B{$row}", 'TURNO B');
            $this->styleSectionTitle($sheet, $row, 'B', 'G');
            $row++;

            foreach ($grupoB as $p) {
                $arma = $this->obtenerArmamento($p);

                $sheet->setCellValue("B{$row}", $contador++);
                $sheet->setCellValue("C{$row}", $p->grado);
                $sheet->setCellValue("D{$row}", $p->nombres);
                $sheet->setCellValue("E{$row}", 'E');
                $sheet->setCellValue("F{$row}", $arma['arma']);
                $sheet->setCellValue("G{$row}", $arma['matricula']);
                $this->styleDataRow($sheet, $row);
                $row++;
            }
        }

        if ($grupoM->isNotEmpty()) {
            $sheet->setCellValue("B{$row}", 'TURNO MIXTO');
            $this->styleSectionTitle($sheet, $row, 'B', 'G');
            $row++;

            foreach ($grupoM as $p) {
                $arma = $this->obtenerArmamento($p);

                $sheet->setCellValue("B{$row}", $contador++);
                $sheet->setCellValue("C{$row}", $p->grado);
                $sheet->setCellValue("D{$row}", $p->nombres);
                $sheet->setCellValue("E{$row}", 'E');
                $sheet->setCellValue("F{$row}", $arma['arma']);
                $sheet->setCellValue("G{$row}", $arma['matricula']);
                $this->styleDataRow($sheet, $row);
                $row++;
            }
        }

        if ($personals->isEmpty()) {
            $sheet->mergeCells("B{$row}:G{$row}");
            $sheet->setCellValue("B{$row}", 'SIN PERSONAL ACTIVO CON ARMAMENTO ASIGNADO PARA EL ÁREA SELECCIONADA');
            $sheet->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
            $sheet->getStyle("B{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        $sheet->mergeCells("D{$row}:G{$row}");
        $sheet->setCellValue("D{$row}", 'R E S P E T U O S A M E N T E');
        $sheet->getStyle("D{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $sheet->mergeCells("D{$row}:G{$row}");
        $sheet->setCellValue("D{$row}", 'ENCARGADO DEL AGRUPAMIENTO DE EQUINOS Y CANINOS');
        $sheet->getStyle("D{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        $nombreFirma = 'CMTE. FREDY ERASTO GONZALEZ OROZCO.';
        if ($siemprePrimero->isNotEmpty()) {
            $nombreFirma = 'CMTE. ' . mb_strtoupper((string)$siemprePrimero->first()->nombres, 'UTF-8') . '.';
        }

        $sheet->mergeCells("D{$row}:G{$row}");
        $sheet->setCellValue("D{$row}", $nombreFirma);
        $sheet->getStyle("D{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("B17:G{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("B9:G{$row}")->getFont()->setName('Arial')->setSize(10);
        $sheet->getStyle("B17:G18")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getDefaultRowDimension()->setRowHeight(18);

        $dir = "daily_reports/{$fecha}/turno_{$turno_id}";
        Storage::disk('local')->makeDirectory($dir);

        $path = "{$dir}/{$filename}";

        $tmp = storage_path('app/tmp_' . Str::uuid() . '.xlsx');
        (new Xlsx($spreadsheet))->save($tmp);

        Storage::disk('local')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $path;
    }

    private function obtenerArmamento(Personal $personal): array
    {
        $asignacion = $personal->asignacionesArmamento
            ? $personal->asignacionesArmamento->first()
            : null;

        if (!$asignacion) {
            return [
                'arma' => 'PENDIENTE',
                'matricula' => 'PENDIENTE',
            ];
        }

        $weapon = $asignacion->weapon ?? null;

        if (!$weapon) {
            return [
                'arma' => 'PENDIENTE',
                'matricula' => 'PENDIENTE',
            ];
        }

        $arma = trim((string)($weapon->marca ?? ''));
        if ($arma === '') {
            $arma = trim((string)($weapon->modelo ?? ''));
        }
        if ($arma === '') {
            $arma = 'PENDIENTE';
        }

        $matricula = trim((string)($weapon->matricula ?? ''));
        if ($matricula === '') {
            $matricula = 'PENDIENTE';
        }

        return [
            'arma' => mb_strtoupper($arma, 'UTF-8'),
            'matricula' => mb_strtoupper($matricula, 'UTF-8'),
        ];
    }

    private function styleHeaderTop($sheet, int $row): void
    {
        $sheet->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("B{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$row}:G{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E1F2');
    }

    private function styleHeaderBottom($sheet, int $row): void
    {
        $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("F{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$row}:G{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("F{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E1F2');
    }

    private function styleSectionTitle($sheet, int $row, string $fromCol, string $toCol): void
    {
        $sheet->mergeCells("{$fromCol}{$row}:{$toCol}{$row}");
        $sheet->getStyle("{$fromCol}{$row}:{$toCol}{$row}")->getFont()->setBold(true);
        $sheet->getStyle("{$fromCol}{$row}:{$toCol}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("{$fromCol}{$row}:{$toCol}{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EDEDED');
    }

    private function styleDataRow($sheet, int $row): void
    {
        $sheet->getStyle("B{$row}:G{$row}")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("B{$row}:C{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("E{$row}:G{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function ponerEncargadosDeTurnoAlInicio($grupo, string $claveTurno)
    {
        $claveTurnoUp = mb_strtoupper($claveTurno, 'UTF-8');

        $enc = $grupo->filter(function ($p) use ($claveTurnoUp) {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''), 'UTF-8');
            return str_contains($cargo, 'ENCARGADO TURNO ' . $claveTurnoUp);
        })->values();

        $resto = $grupo->reject(function ($p) use ($claveTurnoUp) {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''), 'UTF-8');
            return str_contains($cargo, 'ENCARGADO TURNO ' . $claveTurnoUp);
        })->values();

        return $enc->concat($resto)->values();
    }
}

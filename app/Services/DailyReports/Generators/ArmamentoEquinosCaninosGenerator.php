<?php

namespace App\Services\DailyReports\Generators;

use Illuminate\Support\Facades\Storage;
use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArmamentoEquinosCaninosGenerator implements DailyReportGenerator
{
    public function tipo(): string
    {
        return 'armamento_equinos_caninos';
    }

    public function label(): string
    {
        return 'Armamento Equinos y Caninos';
    }

    public function extension(): string
    {
        return 'xlsx';
    }

    public function generar(string $fecha, int $turno_id, array $params = []): string
    {
        $area = trim((string)($params['area'] ?? ''));
        if ($area === '') {
            $area = 'AGRUPAMIENTO DE EQUINOS Y CANINOS';
        }

        $tz = 'America/Mexico_City';
        $fechaC = Carbon::parse($fecha, $tz);
        $fecha_titulo = $fechaC->format('d-m-Y');
        $fecha_texto = mb_strtoupper($fechaC->translatedFormat('d \\D\\E F \\D\\E Y'));

        $turno = Turno::query()->find($turno_id);
        $turno_clave = (string)($turno?->clave ?? '');

        $area_archivo = trim(str_ireplace('AGRUPAMIENTO DE ', '', $area));
        $filename = $fecha_titulo . ' ARMAMENTO ' . mb_strtoupper($area_archivo) . ' TURNO ' . mb_strtoupper($turno_clave) . '.xlsx';

        $personals = Personal::query()
            ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
            ->where('personals.activo', 1)
            ->where(function ($q) use ($area) {
                $q->whereRaw('TRIM(UPPER(areas.nombre)) = ?', [mb_strtoupper(trim($area))]);
            })
            ->whereHas('asignacionesArmamento', function ($q) {
                $q->whereNull('fecha_devolucion')
                    ->where('status', 'ASIGNADA')
                    ->whereHas('weapon', function ($w) {
                        $w->where('estado', 'ACTIVA');
                    });
            })
            ->select('personals.*')
            ->orderBy('personals.grado')
            ->orderBy('personals.nombres')
            ->get();

        if ($personals->isEmpty()) {
            abort(404, 'No hay personal activo con armamento asignado para esa área.');
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

        $esJefeAgrupamiento = function (Personal $p): bool {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''));
            return str_contains($cargo, 'ENCARGADO DE AGRUPAMIENTO');
        };

        $esSiemprePrimero = function (Personal $p): bool {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''));
            $nombre = mb_strtoupper((string)($p->nombres ?? ''));
            return str_contains($cargo, 'SUBDIRECTOR')
                || str_contains($cargo, 'CMTE. FREDY ERASTO')
                || str_contains($nombre, 'FREDY ERASTO GONZALEZ OROZCO');
        };

        $siemprePrimero = $personals->filter(fn ($p) => $esSiemprePrimero($p))->values();

        $personalsSinSiemprePrimero = $personals->reject(fn ($p) => $esSiemprePrimero($p))->values();

        $encargadosAgrupamiento = $personalsSinSiemprePrimero->filter(fn ($p) => $esJefeAgrupamiento($p))->values();
        $resto = $personalsSinSiemprePrimero->reject(fn ($p) => $esJefeAgrupamiento($p))->values();

        $grupoA = collect();
        $grupoB = collect();
        $grupoM = collect();

        foreach ($resto as $p) {
            $tId = $turnoPorPersonal->get($p->id)->turno_id ?? null;
            $clave = $tId ? ($turnos->get($tId)->clave ?? null) : null;
            $clave = mb_strtoupper(trim((string)$clave));

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
        $sheet->setCellValue('A1', mb_strtoupper($area));
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
        $sheet->fromArray(['No.', 'GRADO', 'NOMBRE', 'ENTRADA', 'SALIDA', 'HORARIO'], null, "A{$startRow}");
        $this->styleHeaderRow($sheet, $startRow);

        $row = $startRow + 1;
        $contador = 1;

        foreach ($siemprePrimero as $p) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", $p->grado);
            $sheet->setCellValue("C{$row}", $p->nombres);
            $sheet->setCellValue("F{$row}", 'DISPONIBLE 24 HORAS');
            $this->styleDataRow($sheet, $row);
            $row++;
        }

        foreach ($encargadosAgrupamiento as $enc) {
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

        $nombreEnc = (string)($encargadosAgrupamiento->first()?->nombres ?? '');
        if ($nombreEnc === '' && $siemprePrimero->isNotEmpty()) {
            $nombreEnc = (string)($siemprePrimero->first()?->nombres ?? '');
        }

        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", $nombreEnc !== '' ? ('CMTE. ' . $nombreEnc) : '');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$startRow}:F{$row}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $dir = "daily_reports/{$fecha}/turno_{$turno_id}";
        Storage::disk('local')->makeDirectory($dir);

        $suffix = '';
        if (!empty($params['area'])) {
            $suffix = '_' . Str::slug($area, '_');
        }

        $path = "{$dir}/{$filename}";

        $tmp = storage_path('app/tmp_armamento_' . uniqid() . '.xlsx');
        (new Xlsx($spreadsheet))->save($tmp);

        Storage::disk('local')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $path;
    }

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

    private function ponerEncargadosDeTurnoAlInicio($grupo, string $claveTurno)
    {
        $claveTurnoUp = mb_strtoupper($claveTurno);

        $enc = $grupo->filter(function ($p) use ($claveTurnoUp) {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''));
            return str_contains($cargo, 'ENCARGADO TURNO ' . $claveTurnoUp);
        })->values();

        $resto = $grupo->reject(function ($p) use ($claveTurnoUp) {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''));
            return str_contains($cargo, 'ENCARGADO TURNO ' . $claveTurnoUp);
        })->values();

        return $enc->concat($resto)->values();
    }
}

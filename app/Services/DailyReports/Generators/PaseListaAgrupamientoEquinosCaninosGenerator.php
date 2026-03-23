<?php

namespace App\Services\DailyReports\Generators;

use App\Models\Personal;
use App\Models\ServiceSchedule;
use App\Models\Turno;
use App\Services\DailyReports\Contracts\DailyReportGenerator;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PaseListaAgrupamientoEquinosCaninosGenerator implements DailyReportGenerator
{
    public function tipo(): string
    {
        return 'pase_lista_agrupamiento_equinos_caninos';
    }

    public function label(): string
    {
        return 'Pase de Lista Agrupamiento de Equinos y Caninos';
    }

    public function extension(): string
    {
        return 'xlsx';
    }

    public function generar(string $fecha, int $turno_id, array $params = []): string
    {
        $tz = 'America/Mexico_City';
        $fechaCarbon  = Carbon::parse($fecha, $tz);
        $fechaArchivo = $fechaCarbon->format('d-m-Y');
        $fechaTexto   = mb_strtoupper($fechaCarbon->translatedFormat('d \\D\\E F \\D\\E Y'));

        $tituloArea = $params['titulo_area'] ?? 'AGRUPAMIENTO DE EQUINOS Y CANINOS';

        $turno = Turno::query()->find($turno_id);
        $turnoClave = mb_strtoupper(trim((string)($turno->clave ?? '')));

        $personals = Personal::query()
            ->leftJoin('areas', 'personals.area_id', '=', 'areas.id')
            ->where('personals.activo', 1)
            ->where(function ($q) {
                $q->whereRaw('TRIM(UPPER(areas.nombre)) = ?', ['AGRUPAMIENTO DE EQUINOS Y CANINOS'])
                  ->orWhereRaw('TRIM(UPPER(areas.nombre)) = ?', ['AGRUPAMIENTO DE EQUINOS CANINOS']);
            })
            ->select('personals.*')
            ->orderBy('personals.grado')
            ->orderBy('personals.nombres')
            ->get();

        if ($personals->isEmpty()) {
            throw new \RuntimeException('No hay personal activo en el área AGRUPAMIENTO DE EQUINOS Y CANINOS para generar el pase de lista.');
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

        $incidenciasActivas = $this->cargarIncidenciasActivas($personals->pluck('id')->all(), $fechaCarbon);
        $horariosMixtos     = $this->cargarHorariosPersonalizados($personals->pluck('id')->all());

        $esResponsablePrincipal = function (Personal $p): bool {
            $cargo = mb_strtoupper(trim((string)($p->cargo ?? '')));
            return str_contains($cargo, 'ENCARGADO DE AGRUPAMIENTO')
                || str_contains($cargo, 'ENCARGADO DE AREA')
                || str_contains($cargo, 'ENCARGADO DE ÁREA');
        };

        $encargados = $personals
            ->filter(fn ($p) => $esResponsablePrincipal($p))
            ->values();

        $resto = $personals
            ->reject(fn ($p) => $esResponsablePrincipal($p))
            ->values();

        $grupoA = collect();
        $grupoB = collect();
        $grupoM = collect();

        foreach ($resto as $p) {
            $clave = $this->resolverClaveTurno($p, $turnoPorPersonal, $turnos, $horariosMixtos);

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
        $grupoM = $this->ordenarGrupoMixto($grupoM);

        $forzarFrancoA = ($turnoClave === 'B');
        $forzarFrancoB = ($turnoClave === 'A');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PASE LISTA');

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(16);
        $sheet->getColumnDimension('C')->setWidth(44);
        $sheet->getColumnDimension('D')->setWidth(28);

        $logoPath = public_path('img/Imagen2.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(65);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'SECRETARÍA DE SEGURIDAD PÚBLICA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:D3');
        $sheet->setCellValue('A3', mb_strtoupper($tituloArea));
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A5:D5');
        $sheet->setCellValue('A5', $fechaTexto);
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $startRow = 7;
        $sheet->fromArray(['No.', 'GRADO', 'NOMBRE', 'OBSERVACIONES'], null, "A{$startRow}");
        $this->styleHeaderRow($sheet, $startRow);

        $row = $startRow + 1;
        $contador = 1;

        foreach ($encargados as $enc) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", mb_strtoupper((string)$enc->grado));
            $sheet->setCellValue("C{$row}", mb_strtoupper((string)$enc->nombres));
            $sheet->setCellValue("D{$row}", '');
            $this->styleDataRow($sheet, $row);
            $row++;
        }

        $row = $this->printTurnSection(
            $sheet,
            $row,
            'PERSONAL TURNO A',
            $grupoA,
            $contador,
            $forzarFrancoA,
            false,
            $fechaCarbon,
            $incidenciasActivas,
            $horariosMixtos
        );
        $contador += $grupoA->count();

        $row = $this->printTurnSection(
            $sheet,
            $row,
            'PERSONAL TURNO B',
            $grupoB,
            $contador,
            $forzarFrancoB,
            false,
            $fechaCarbon,
            $incidenciasActivas,
            $horariosMixtos
        );
        $contador += $grupoB->count();

        $row = $this->printTurnSection(
            $sheet,
            $row,
            'PERSONAL QUE LABORA DIARIO',
            $grupoM,
            $contador,
            false,
            true,
            $fechaCarbon,
            $incidenciasActivas,
            $horariosMixtos
        );
        $contador += $grupoM->count();

        $ultimoRow = $row - 1;

        $sheet->getStyle("A{$startRow}:D{$ultimoRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$startRow}:D{$ultimoRow}")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        foreach (range(1, $ultimoRow) as $r) {
            $sheet->getRowDimension($r)->setRowHeight(22);
        }

        $fileName = $fechaArchivo . ' PASE DE LISTA AGRUPAMIENTO EQUINOS Y CANINOS.xlsx';
        $path = "daily_reports/{$fecha}/turno_{$turno_id}/{$fileName}";

        Storage::disk('local')->makeDirectory("daily_reports/{$fecha}/turno_{$turno_id}");

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/' . $path));

        return $path;
    }

    private function styleHeaderRow($sheet, int $row): void
    {
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("A{$row}:D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:D{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('8EA9DB');
    }

    private function styleSectionTitle($sheet, int $row): void
    {
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("A{$row}:D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:D{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('8EA9DB');
    }

    private function styleDataRow($sheet, int $row): void
    {
        $sheet->getStyle("A{$row}:D{$row}")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A{$row}:B{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("D{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function printTurnSection(
        $sheet,
        int $row,
        string $titulo,
        Collection $personals,
        int $contadorInicio,
        bool $forzarFranco,
        bool $esMixto,
        Carbon $fecha,
        array $incidenciasActivas,
        array $horariosMixtos
    ): int {
        if ($personals->isEmpty()) {
            return $row;
        }

        $sheet->setCellValue("A{$row}", $titulo);
        $this->styleSectionTitle($sheet, $row);
        $row++;

        $contador = $contadorInicio;

        foreach ($personals as $p) {
            $sheet->setCellValue("A{$row}", $contador++);
            $sheet->setCellValue("B{$row}", mb_strtoupper((string)$p->grado));
            $sheet->setCellValue("C{$row}", mb_strtoupper((string)$p->nombres));
            $sheet->setCellValue(
                "D{$row}",
                $this->resolverObservacion($p, $fecha, $incidenciasActivas, $forzarFranco, $esMixto, $horariosMixtos)
            );
            $this->styleDataRow($sheet, $row);
            $row++;
        }

        return $row;
    }

    private function resolverObservacion(
        Personal $p,
        Carbon $fecha,
        array $incidenciasActivas,
        bool $forzarFranco,
        bool $esMixto,
        array $horariosMixtos
    ): string {
        $inc = $incidenciasActivas[$p->id] ?? null;

        if ($inc && (int)($inc['afecta_servicio'] ?? 0) === 1) {
            $texto = $this->normalizarTextoIncidencia(
                (string)($inc['clave'] ?? ''),
                (string)($inc['nombre'] ?? '')
            );

            if ($texto !== '') {
                return $texto;
            }
        }

        $cargo = mb_strtoupper(trim((string)($p->cargo ?? '')));

        if (str_contains($cargo, 'MEDICO')) {
            return 'DISPONIBLE LAS 24 HORAS';
        }

        if ($esMixto) {
            $horario = $this->horarioPersonalizadoDelDia($p->id, $fecha, $horariosMixtos);

            if ($horario !== '') {
                return $horario;
            }

            return '';
        }

        if ($forzarFranco) {
            return 'FRANCO';
        }

        return '';
    }

    private function cargarIncidenciasActivas(array $personalIds, Carbon $fecha): array
    {
        if (empty($personalIds)) {
            return [];
        }

        $rows = DB::table('incidences as i')
            ->leftJoin('incidence_types as it', 'it.id', '=', 'i.incidence_type_id')
            ->whereIn('i.personal_id', $personalIds)
            ->select([
                'i.personal_id',
                'i.incidence_type_id',
                'i.fecha_inicio',
                'i.fecha_fin',
                'i.comentario',
                'it.clave as incidencia_clave',
                'it.nombre as incidencia_nombre',
                'it.afecta_servicio as afecta_servicio',
            ])
            ->orderBy('i.personal_id')
            ->orderBy('i.fecha_inicio', 'desc')
            ->get();

        $fechaConsulta = $fecha->toDateString();
        $map = [];

        foreach ($rows as $r) {
            $pid = (int)$r->personal_id;

            if (isset($map[$pid])) {
                continue;
            }

            $clave = mb_strtoupper(trim((string)($r->incidencia_clave ?? $this->mapearClaveIncidenciaPorId($r->incidence_type_id ?? null))));
            $inicio = !empty($r->fecha_inicio) ? Carbon::parse($r->fecha_inicio)->toDateString() : null;
            $fin    = !empty($r->fecha_fin) ? Carbon::parse($r->fecha_fin)->toDateString() : null;

            $esActiva = false;

            if ($inicio !== null) {
                if ($fin !== null) {
                    $esActiva = ($fechaConsulta >= $inicio && $fechaConsulta <= $fin);
                } else {
                    if (in_array($clave, ['FALTA'], true)) {
                        $esActiva = ($fechaConsulta === $inicio);
                    } else {
                        $esActiva = ($fechaConsulta >= $inicio);
                    }
                }
            }

            if (!$esActiva) {
                continue;
            }

            $map[$pid] = [
                'clave' => $clave,
                'nombre' => (string)($r->incidencia_nombre ?? ''),
                'comentario' => $r->comentario ?? null,
                'afecta_servicio' => (int)($r->afecta_servicio ?? 1),
            ];
        }

        return $map;
    }

    private function mapearClaveIncidenciaPorId($id): string
    {
        return match ((int)$id) {
            1 => 'VACACIONES',
            2 => 'PROCESO ADMINISTRATIVO',
            3 => 'FALTA',
            4 => 'LICENCIA LABORAL',
            default => '',
        };
    }

    private function normalizarTextoIncidencia(string $clave, string $nombre): string
    {
        $clave = mb_strtoupper(trim($clave));
        $nombre = mb_strtoupper(trim($nombre));

        if ($clave !== '') {
            if (str_contains($clave, 'VACACIONES')) return 'VACACIONES';
            if (str_contains($clave, 'LICENCIA LABORAL')) return 'LICENCIA LABORAL';
            if (str_contains($clave, 'FALTA')) return 'FALTA';
            if (str_contains($clave, 'PROCESO ADMINISTRATIVO')) return 'PROCESO ADMINISTRATIVO';
            if (str_contains($clave, 'TRAMITE_DE_BAJA')) return 'PROCESO ADMINISTRATIVO';
            if (str_contains($clave, 'TRAMITE DE BAJA')) return 'PROCESO ADMINISTRATIVO';
            if (str_contains($clave, 'FRANCO')) return 'FRANCO';
            if (str_contains($clave, 'INCAPACIDAD')) return 'INCAPACIDAD';
            if (str_contains($clave, 'PERMISO')) return 'PERMISO';
            if (str_contains($clave, 'COMISION')) return 'COMISIÓN';
            if (str_contains($clave, 'SUSPENSION')) return 'SUSPENSIÓN';
            if (str_contains($clave, 'DISPONIBLE')) return 'DISPONIBLE LAS 24 HORAS';
        }

        if ($nombre !== '') {
            if (str_contains($nombre, 'VACACION')) return 'VACACIONES';
            if (str_contains($nombre, 'LICENCIA')) return 'LICENCIA LABORAL';
            if (str_contains($nombre, 'FALTA')) return 'FALTA';
            if (str_contains($nombre, 'TRAMITE DE BAJA')) return 'PROCESO ADMINISTRATIVO';
            if (str_contains($nombre, 'PROCESO ADMINISTRATIVO')) return 'PROCESO ADMINISTRATIVO';
            if (str_contains($nombre, 'FRANCO')) return 'FRANCO';
            if (str_contains($nombre, 'INCAPACIDAD')) return 'INCAPACIDAD';
            if (str_contains($nombre, 'PERMISO')) return 'PERMISO';
            if (str_contains($nombre, 'COMISION')) return 'COMISIÓN';
            if (str_contains($nombre, 'SUSPENSION')) return 'SUSPENSIÓN';
            if (str_contains($nombre, 'DISPONIBLE')) return 'DISPONIBLE LAS 24 HORAS';
        }

        return '';
    }

    private function resolverClaveTurno(
        Personal $p,
        Collection $turnoPorPersonal,
        Collection $turnos,
        array $horariosMixtos
    ): string {
        $turnoId = $turnoPorPersonal->get($p->id)->turno_id ?? $p->turno_id ?? null;
        $clave = '';

        if ($turnoId) {
            $clave = mb_strtoupper(trim((string)($turnos->get($turnoId)->clave ?? '')));
        }

        if (in_array($clave, ['A', 'B', 'MIXTO'], true)) {
            return $clave;
        }

        $cargo = mb_strtoupper(trim((string)($p->cargo ?? '')));

        if (str_contains($cargo, 'ENCARGADO TURNO A')) {
            return 'A';
        }

        if (str_contains($cargo, 'ENCARGADO TURNO B')) {
            return 'B';
        }

        if (isset($horariosMixtos[$p->id]) && !empty($horariosMixtos[$p->id])) {
            return 'MIXTO';
        }

        return 'B';
    }

    private function ponerEncargadosDeTurnoAlInicio(Collection $grupo, string $claveTurno): Collection
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

    private function ordenarGrupoMixto(Collection $grupo): Collection
    {
        return $grupo->sortBy(function ($p) {
            $cargo = mb_strtoupper((string)($p->cargo ?? ''));

            if (str_contains($cargo, 'MEDICO')) return 1;
            if (str_contains($cargo, 'T.E.M.P')) return 2;
            return 3;
        })->values();
    }

    private function cargarHorariosPersonalizados(array $personalIds): array
    {
        if (empty($personalIds)) {
            return [];
        }

        if (!Schema::hasTable('personal_horarios') || !Schema::hasTable('personal_horario_detalles')) {
            return [];
        }

        $rows = DB::table('personal_horarios as ph')
            ->join('personal_horario_detalles as phd', 'ph.id', '=', 'phd.personal_horario_id')
            ->whereIn('ph.personal_id', $personalIds)
            ->select([
                'ph.personal_id',
                'phd.dia_semana',
                'phd.hora_entrada',
                'phd.hora_salida',
                'phd.cruza_dia',
                'phd.bloque',
            ])
            ->orderBy('ph.personal_id')
            ->orderBy('phd.dia_semana')
            ->orderBy('phd.hora_entrada')
            ->get();

        $map = [];

        foreach ($rows as $r) {
            $pid = (int)$r->personal_id;
            $dia = (int)$r->dia_semana;

            if (!isset($map[$pid])) {
                $map[$pid] = [];
            }

            if (!isset($map[$pid][$dia])) {
                $map[$pid][$dia] = [];
            }

            $map[$pid][$dia][] = [
                'entrada' => $r->hora_entrada,
                'salida'  => $r->hora_salida,
                'cruza'   => (int)$r->cruza_dia,
                'bloque'  => $r->bloque,
            ];
        }

        return $map;
    }

    private function horarioPersonalizadoDelDia(int $personalId, Carbon $fecha, array $horariosMixtos): string
    {
        $dia = (int)$fecha->dayOfWeek;
        $bloques = $horariosMixtos[$personalId][$dia] ?? [];

        if (empty($bloques)) {
            return '';
        }

        $partes = [];

        foreach ($bloques as $b) {
            $entrada = $this->horaSimple($b['entrada'] ?? null);
            $salida = $this->horaSimple($b['salida'] ?? null);

            if ($entrada !== '' && $salida !== '') {
                $texto = $entrada . '-' . $salida;

                if ((int)($b['cruza'] ?? 0) === 1) {
                    $texto .= ' (+1 DÍA)';
                }

                $partes[] = $texto;
            }
        }

        if (empty($partes)) {
            return '';
        }

        return implode(' / ', $partes);
    }

    private function horaSimple(?string $hora): string
    {
        $hora = trim((string)$hora);

        if ($hora === '') {
            return '';
        }

        return substr($hora, 0, 5);
    }
}

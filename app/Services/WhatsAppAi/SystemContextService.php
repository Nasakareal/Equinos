<?php

namespace App\Services\WhatsAppAi;

use App\Models\Actividad;
use App\Models\Animal;
use App\Models\AnimalIncidence;
use App\Models\AnimalMedicalRecord;
use App\Models\Area;
use App\Models\EquinoterapiaReporte;
use App\Models\Incidence;
use App\Models\Patrol;
use App\Models\PatrolAssignment;
use App\Models\Personal;
use App\Models\PuestaDisposicion;
use App\Models\Servicio;
use App\Models\ServicioReporte;
use App\Models\Weapon;
use App\Models\WeaponAssignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class SystemContextService
{
    public function buildForQuestion(string $question, bool $privileged = false): string
    {
        $sections = [
            $this->buildExecutiveSnapshot(),
            $this->buildRecentOperations(),
            $this->buildSearchContext($question),
        ];

        if ($privileged) {
            $sections[] = $this->buildSensitiveOperationalSnapshot();
        }

        return trim(implode("\n\n", array_filter($sections)));
    }

    protected function buildExecutiveSnapshot(): string
    {
        return $this->safeSection(function () {
            $lines = ['RESUMEN DEL SISTEMA'];
            $personnelTotals = $this->personnelInventoryTotals();
            $animalTotals = $this->animalInventoryTotals();

            $lines[] = '- Personal: ' . $this->formatPersonnelTotals($personnelTotals);
            $lines[] = '- Animales registrados: ' . $animalTotals['total'];
            $lines[] = '- Equinos: ' . $this->formatAnimalTypeTotals($animalTotals['types']['EQUINO']);
            $lines[] = '- Caninos: ' . $this->formatAnimalTypeTotals($animalTotals['types']['CANINO']);
            $lines[] = '- Patrullas activas: ' . Patrol::query()->where('estado', 'ACTIVO')->count();
            $lines[] = '- Armas registradas: ' . Weapon::query()->count();
            $lines[] = '- Servicios del mes: ' . Servicio::query()
                ->whereBetween('fecha', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->count();
            $lines[] = '- Reportes de servicio del mes: ' . ServicioReporte::query()
                ->whereBetween('fecha', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->count();
            $lines[] = '- Puestas a disposicion del anio: ' . PuestaDisposicion::query()
                ->where('anio', now()->year)
                ->count();

            $areas = Area::query()
                ->withCount([
                    'personals as total_personal_count',
                    'personals as personal_activo_count' => function ($query) {
                        $query->where('activo', 1);
                    },
                ])
                ->orderBy('nombre')
                ->limit(12)
                ->get();

            if ($areas->isNotEmpty()) {
                $lines[] = 'AREAS DE PERSONAL (NO SON CONTEOS DE ANIMALES)';

                foreach ($areas as $area) {
                    $total = (int) $area->total_personal_count;
                    $active = (int) $area->personal_activo_count;
                    $inactive = max(0, $total - $active);

                    $lines[] = '- Area ' . trim((string) $area->nombre) . ': ' . $total
                        . ' elementos registrados (' . $active . ' activos, ' . $inactive . ' inactivos)';
                }
            }

            return implode("\n", $lines);
        }, 'No se pudo armar el resumen general.');
    }

    protected function buildRecentOperations(): string
    {
        return $this->safeSection(function () {
            $lines = ['NOVEDADES RECIENTES'];

            $servicios = Servicio::query()
                ->with(['personal', 'canino', 'equino', 'patrulla'])
                ->orderByDesc('fecha')
                ->orderByDesc('hora')
                ->limit(8)
                ->get();

            if ($servicios->isNotEmpty()) {
                $lines[] = 'SERVICIOS';

                foreach ($servicios as $servicio) {
                    $lines[] = '- ' . $this->formatServicio($servicio);
                }
            }

            $reportes = ServicioReporte::query()
                ->with('servicio')
                ->orderByDesc('fecha')
                ->orderByDesc('hora')
                ->limit(8)
                ->get();

            if ($reportes->isNotEmpty()) {
                $lines[] = 'REPORTES';

                foreach ($reportes as $reporte) {
                    $lines[] = '- ' . $this->formatReporte($reporte);
                }
            }

            $incidencias = AnimalIncidence::query()
                ->with(['animal', 'incidenceType'])
                ->orderByDesc('fecha')
                ->limit(6)
                ->get();

            if ($incidencias->isNotEmpty()) {
                $lines[] = 'INCIDENCIAS DE ANIMALES';

                foreach ($incidencias as $incidencia) {
                    $animal = $incidencia->animal ? $incidencia->animal->nombre : 'Sin animal';
                    $tipo = $incidencia->incidenceType ? $incidencia->incidenceType->nombre : 'Sin tipo';
                    $lines[] = '- ' . $this->dateValue($incidencia->fecha) . ' | ' . $animal . ' | ' . $tipo . ' | ' . $this->limitText($incidencia->descripcion, 140);
                }
            }

            $proximasCitas = AnimalMedicalRecord::query()
                ->with('animal')
                ->whereNotNull('proxima_cita')
                ->whereDate('proxima_cita', '>=', now()->toDateString())
                ->orderBy('proxima_cita')
                ->limit(6)
                ->get();

            if ($proximasCitas->isNotEmpty()) {
                $lines[] = 'CITAS MEDICAS PROXIMAS';

                foreach ($proximasCitas as $cita) {
                    $animal = $cita->animal ? $cita->animal->nombre : 'Sin animal';
                    $lines[] = '- ' . $this->dateValue($cita->proxima_cita) . ' | ' . $animal . ' | ' . $this->limitText($cita->tipo . ': ' . $cita->descripcion, 140);
                }
            }

            $equinoterapia = EquinoterapiaReporte::query()
                ->orderByDesc('fecha')
                ->limit(3)
                ->get();

            if ($equinoterapia->isNotEmpty()) {
                $lines[] = 'EQUINOTERAPIA';

                foreach ($equinoterapia as $reporte) {
                    $lines[] = '- ' . $this->dateValue($reporte->fecha)
                        . ' | valoraciones: ' . (int) $reporte->valoraciones
                        . ' | personal: ' . (int) $reporte->personal
                        . ' | equinos: ' . (int) $reporte->equinos
                        . ' | ' . $this->limitText($reporte->observaciones, 120);
                }
            }

            return implode("\n", $lines);
        }, 'No se pudieron leer novedades recientes.');
    }

    protected function buildSensitiveOperationalSnapshot(): string
    {
        return $this->safeSection(function () {
            $lines = ['CONTEXTO OPERATIVO AMPLIADO'];

            $armamento = WeaponAssignment::query()
                ->with(['personal', 'weapon'])
                ->where(function ($query) {
                    $query->whereNull('fecha_devolucion')
                        ->orWhere('status', 'ASIGNADA');
                })
                ->orderByDesc('fecha_asignacion')
                ->limit(12)
                ->get();

            if ($armamento->isNotEmpty()) {
                $lines[] = 'ARMAMENTO ASIGNADO';

                foreach ($armamento as $asignacion) {
                    $personal = $asignacion->personal ? $asignacion->personal->nombres : 'Sin personal';
                    $weapon = $asignacion->weapon ? trim($asignacion->weapon->tipo . ' ' . $asignacion->weapon->marca_modelo . ' ' . $asignacion->weapon->matricula) : 'Sin arma';
                    $lines[] = '- ' . $personal . ' | ' . $weapon . ' | ' . $this->dateValue($asignacion->fecha_asignacion);
                }
            }

            $patrullas = PatrolAssignment::query()
                ->with(['patrol', 'turno', 'personals'])
                ->orderByDesc('fecha')
                ->limit(8)
                ->get();

            if ($patrullas->isNotEmpty()) {
                $lines[] = 'ASIGNACIONES DE PATRULLA';

                foreach ($patrullas as $asignacion) {
                    $patrulla = $asignacion->patrol ? $asignacion->patrol->numero_economico : 'Sin patrulla';
                    $personal = $asignacion->personals->pluck('nombres')->filter()->implode(', ');
                    $lines[] = '- ' . $this->dateValue($asignacion->fecha) . ' | ' . $patrulla . ' | ' . $this->limitText($personal, 160);
                }
            }

            $incidenciasPersonal = Incidence::query()
                ->with(['personal', 'tipo'])
                ->orderByDesc('fecha_inicio')
                ->limit(8)
                ->get();

            if ($incidenciasPersonal->isNotEmpty()) {
                $lines[] = 'INCIDENCIAS DE PERSONAL';

                foreach ($incidenciasPersonal as $incidencia) {
                    $personal = $incidencia->personal ? $incidencia->personal->nombres : 'Sin personal';
                    $tipo = $incidencia->tipo ? $incidencia->tipo->nombre : 'Sin tipo';
                    $lines[] = '- ' . $this->dateValue($incidencia->fecha_inicio) . ' | ' . $personal . ' | ' . $tipo . ' | ' . $this->limitText($incidencia->comentario, 120);
                }
            }

            return implode("\n", $lines);
        }, 'No se pudo leer contexto operativo ampliado.');
    }

    protected function buildSearchContext(string $question): string
    {
        $tokens = $this->tokens($question);

        if (empty($tokens)) {
            return '';
        }

        return $this->safeSection(function () use ($tokens) {
            $lines = ['RESULTADOS RELACIONADOS CON LA PREGUNTA'];
            $personnelAreaName = $this->personnelAreaNameFromTokens($tokens);

            if ($this->isPersonnelInventoryContextNeeded($tokens)) {
                $lines[] = $this->buildPersonnelInventorySnapshot('CONTEOS EXACTOS DE PERSONAL', $personnelAreaName);
            }

            if ($this->isAnimalInventoryContextNeeded($tokens)) {
                $lines[] = $this->buildAnimalInventorySnapshot('CONTEOS EXACTOS DE ANIMALES');
            }

            $personalQuery = Personal::query()
                ->with(['area', 'turno'])
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['nombres', 'no_empleado', 'cuip', 'crp', 'cargo', 'actividad'], $tokens);
                });

            $personalMatches = (clone $personalQuery)->count();
            $personals = $personalQuery
                ->orderBy('nombres')
                ->limit(8)
                ->get();

            if ($personals->isNotEmpty()) {
                $lines[] = 'PERSONAL ENCONTRADO (muestra de hasta 8 de ' . $personalMatches . ' coincidencias; no es conteo total)';

                foreach ($personals as $personal) {
                    $area = $personal->area ? $personal->area->nombre : null;
                    $turno = $personal->turno ? $personal->turno->nombre : null;
                    $lines[] = '- ' . $personal->nombres
                        . ' | empleado: ' . ($personal->no_empleado ?: 'N/D')
                        . ' | cargo: ' . ($personal->cargo ?: 'N/D')
                        . ' | area: ' . ($area ?: 'N/D')
                        . ' | turno: ' . ($turno ?: 'N/D')
                        . ' | activo: ' . ($personal->activo ? 'SI' : 'NO');
                }
            }

            $animalQuery = Animal::query()
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['tipo', 'nombre', 'raza', 'chip', 'marcaje', 'especialidad', 'estatus', 'caracteristicas'], $tokens);
                });

            $animalMatches = (clone $animalQuery)->count();
            $animals = $animalQuery
                ->orderBy('tipo')
                ->orderBy('nombre')
                ->limit(8)
                ->get();

            if ($animals->isNotEmpty()) {
                $lines[] = 'ANIMALES ENCONTRADOS (muestra de hasta 8 de ' . $animalMatches . ' coincidencias; no es conteo total)';

                foreach ($animals as $animal) {
                    $lines[] = '- ' . $animal->tipo . ' ' . $animal->nombre
                        . ' | raza: ' . ($animal->raza ?: 'N/D')
                        . ' | sexo: ' . ($animal->sexo ?: 'N/D')
                        . ' | estatus: ' . ($animal->estatus ?: 'N/D')
                        . ' | especialidad: ' . ($animal->especialidad ?: 'N/D');
                }
            }

            $servicios = Servicio::query()
                ->with(['personal', 'canino', 'equino', 'patrulla'])
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['asunto', 'municipio', 'lugar', 'descripcion', 'acciones_realizadas', 'resultados', 'observaciones'], $tokens);
                })
                ->orderByDesc('fecha')
                ->limit(8)
                ->get();

            if ($servicios->isNotEmpty()) {
                $lines[] = 'SERVICIOS ENCONTRADOS';

                foreach ($servicios as $servicio) {
                    $lines[] = '- ' . $this->formatServicio($servicio);
                }
            }

            $reportes = ServicioReporte::query()
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['tipo_reporte', 'municipio', 'lugar', 'asunto', 'narrativa', 'acciones_realizadas', 'resultados', 'conclusion'], $tokens);
                })
                ->orderByDesc('fecha')
                ->limit(8)
                ->get();

            if ($reportes->isNotEmpty()) {
                $lines[] = 'REPORTES ENCONTRADOS';

                foreach ($reportes as $reporte) {
                    $lines[] = '- ' . $this->formatReporte($reporte);
                }
            }

            $puestas = PuestaDisposicion::query()
                ->with('personal')
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['folio', 'observaciones'], $tokens);
                })
                ->orderByDesc('anio')
                ->orderByDesc('folio')
                ->limit(8)
                ->get();

            if ($puestas->isNotEmpty()) {
                $lines[] = 'PUESTAS A DISPOSICION';

                foreach ($puestas as $puesta) {
                    $personal = $puesta->personal ? $puesta->personal->nombres : 'Sin personal';
                    $lines[] = '- ' . $puesta->anio . '-' . $puesta->folio . ' | ' . $personal . ' | ' . $this->limitText($puesta->observaciones, 140);
                }
            }

            $actividades = Actividad::query()
                ->with(['categoria', 'subcategoria'])
                ->where(function (Builder $query) use ($tokens) {
                    $this->applyLikeSearch($query, ['nombre'], $tokens);
                })
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            if ($actividades->isNotEmpty()) {
                $lines[] = 'ACTIVIDADES';

                foreach ($actividades as $actividad) {
                    $categoria = $actividad->categoria ? $actividad->categoria->nombre : 'N/D';
                    $subcategoria = $actividad->subcategoria ? $actividad->subcategoria->nombre : 'N/D';
                    $lines[] = '- ' . $actividad->nombre . ' | ' . $categoria . ' / ' . $subcategoria . ' | cantidad: ' . $actividad->cantidad;
                }
            }

            return implode("\n", $lines);
        }, 'No se pudo realizar la busqueda relacionada.');
    }

    protected function applyLikeSearch(Builder $query, array $columns, array $tokens): void
    {
        foreach ($tokens as $token) {
            $query->orWhere(function (Builder $inner) use ($columns, $token) {
                foreach ($columns as $column) {
                    $inner->orWhere($column, 'LIKE', '%' . $token . '%');
                }
            });
        }
    }

    protected function formatServicio(Servicio $servicio): string
    {
        $partes = [
            'id ' . $servicio->id,
            $this->dateValue($servicio->fecha) . ' ' . (string) $servicio->hora,
            (string) ($servicio->tipo_servicio ?: $servicio->categoria_registro),
            (string) ($servicio->municipio ?: 'Sin municipio'),
            $this->limitText($servicio->asunto ?: $servicio->descripcion, 130),
        ];

        if ($servicio->personal) {
            $partes[] = 'personal: ' . $servicio->personal->nombres;
        }

        if ($servicio->canino) {
            $partes[] = 'canino: ' . $servicio->canino->nombre;
        }

        if ($servicio->equino) {
            $partes[] = 'equino: ' . $servicio->equino->nombre;
        }

        if ($servicio->patrulla) {
            $partes[] = 'patrulla: ' . $servicio->patrulla->numero_economico;
        }

        return implode(' | ', array_filter($partes));
    }

    protected function formatReporte(ServicioReporte $reporte): string
    {
        return 'id ' . $reporte->id
            . ' | servicio ' . $reporte->servicio_id
            . ' | ' . $this->dateValue($reporte->fecha) . ' ' . (string) $reporte->hora
            . ' | ' . (string) $reporte->tipo_reporte
            . ' | ' . (string) ($reporte->municipio ?: 'Sin municipio')
            . ' | ' . $this->limitText($reporte->asunto ?: $reporte->narrativa ?: $reporte->resultados, 150);
    }

    protected function tokens(string $question): array
    {
        $question = mb_strtolower($question, 'UTF-8');
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $question);

        if ($ascii !== false) {
            $question = $ascii;
        }

        $question = preg_replace('/[^a-z0-9]+/', ' ', $question);
        $words = preg_split('/\s+/', trim((string) $question));
        $stopwords = [
            'como', 'para', 'dame', 'dime', 'quiero', 'necesito', 'sobre', 'este',
            'esta', 'estos', 'estas', 'cual', 'cuales', 'cuanto', 'cuantos',
            'cuanta', 'cuantas', 'hacer', 'oficio', 'redacta', 'redactame',
            'con', 'del', 'los', 'las', 'una', 'uno', 'por', 'que', 'hay',
            'hoy', 'ayer', 'mes', 'ano', 'anio', 'sistema', 'arriba',
        ];

        $tokens = [];
        $synonyms = [
            'equinos' => ['equino'],
            'equina' => ['equino'],
            'equinas' => ['equino'],
            'caballo' => ['equino'],
            'caballos' => ['equino'],
            'caninos' => ['canino'],
            'canina' => ['canino'],
            'caninas' => ['canino'],
            'perro' => ['canino'],
            'perros' => ['canino'],
            'k9' => ['canino'],
            'animales' => ['animal'],
        ];

        foreach ($words as $word) {
            $word = trim((string) $word);

            if (strlen($word) < 3 || in_array($word, $stopwords, true)) {
                continue;
            }

            $tokens[] = $word;

            foreach ($synonyms[$word] ?? [] as $synonym) {
                $tokens[] = $synonym;
            }
        }

        return array_values(array_unique(array_slice($tokens, 0, 8)));
    }

    protected function isAnimalInventoryContextNeeded(array $tokens): bool
    {
        return !empty(array_intersect($tokens, [
            'animal', 'animales',
            'equino', 'equinos', 'caballo', 'caballos',
            'canino', 'caninos', 'perro', 'perros', 'k9',
        ]));
    }

    protected function isPersonnelInventoryContextNeeded(array $tokens): bool
    {
        return !empty(array_intersect($tokens, [
            'elemento', 'elementos', 'personal', 'personales', 'policia', 'policias',
            'area', 'areas', 'agrupamiento',
        ]));
    }

    protected function buildPersonnelInventorySnapshot(string $title, ?string $areaName = null): string
    {
        if ($areaName !== null) {
            $areaTotals = $this->personnelAreaInventoryTotals($areaName);

            if ($areaTotals !== null) {
                return $title . "\n"
                    . '- Personal del area ' . $areaName . ': ' . $this->formatPersonnelTotals($areaTotals) . "\n"
                    . 'Este conteo es de personal por area, no de animales. No lo compares contra el inventario de equinos/caninos.';
            }
        }

        $totals = $this->personnelInventoryTotals();

        return $title . "\n"
            . '- Personal: ' . $this->formatPersonnelTotals($totals) . "\n"
            . 'Usa este conteo para preguntas de cantidad de elementos o personal; no cuentes las filas de las muestras listadas abajo.';
    }

    protected function personnelInventoryTotals(): array
    {
        $total = Personal::query()->count();
        $active = Personal::query()->where('activo', 1)->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => max(0, $total - $active),
        ];
    }

    protected function personnelAreaNameFromTokens(array $tokens): ?string
    {
        $hasAreaCue = !empty(array_intersect($tokens, [
            'area', 'areas', 'agrupamiento',
            'elemento', 'elementos', 'personal', 'personales', 'policia', 'policias',
        ]));

        if (!$hasAreaCue) {
            return null;
        }

        if (!empty(array_intersect($tokens, ['canino', 'caninos', 'canina', 'caninas', 'k9']))) {
            return 'CANINOS';
        }

        if (!empty(array_intersect($tokens, ['equino', 'equinos', 'equina', 'equinas']))) {
            return 'EQUINOS';
        }

        if (in_array('equinoterapia', $tokens, true)) {
            return 'EQUINOTERAPIA';
        }

        return null;
    }

    protected function personnelAreaInventoryTotals(string $areaName): ?array
    {
        $area = Area::query()
            ->withCount([
                'personals as total_personal_count',
                'personals as personal_activo_count' => function ($query) {
                    $query->where('activo', 1);
                },
            ])
            ->where('nombre', $areaName)
            ->first();

        if (!$area) {
            return null;
        }

        $total = (int) $area->total_personal_count;
        $active = (int) $area->personal_activo_count;

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => max(0, $total - $active),
        ];
    }

    protected function formatPersonnelTotals(array $totals): string
    {
        return $totals['total'] . ' registrados'
            . ' (' . (int) $totals['active'] . ' activos'
            . ', ' . (int) $totals['inactive'] . ' inactivos)';
    }

    protected function buildAnimalInventorySnapshot(string $title): string
    {
        $totals = $this->animalInventoryTotals();

        return $title . "\n"
            . '- Animales registrados: ' . $totals['total'] . "\n"
            . '- Equinos: ' . $this->formatAnimalTypeTotals($totals['types']['EQUINO']) . "\n"
            . '- Caninos: ' . $this->formatAnimalTypeTotals($totals['types']['CANINO']) . "\n"
            . 'Estos son animales registrados (caballos/perros), no personal de las areas EQUINOS/CANINOS. No cuentes las filas de las muestras listadas abajo.';
    }

    protected function animalInventoryTotals(): array
    {
        $statuses = ['ACTIVO', 'BAJA', 'RESGUARDO'];
        $types = [
            'EQUINO' => array_fill_keys(array_merge(['total'], $statuses), 0),
            'CANINO' => array_fill_keys(array_merge(['total'], $statuses), 0),
        ];

        $rows = Animal::query()
            ->selectRaw('tipo, estatus, COUNT(*) as total')
            ->groupBy('tipo', 'estatus')
            ->get();

        foreach ($rows as $row) {
            $type = (string) $row->tipo;
            $status = (string) $row->estatus;

            if (!isset($types[$type])) {
                continue;
            }

            if (!array_key_exists($status, $types[$type])) {
                $types[$type][$status] = 0;
            }

            $count = (int) $row->total;
            $types[$type][$status] += $count;
            $types[$type]['total'] += $count;
        }

        return [
            'total' => $types['EQUINO']['total'] + $types['CANINO']['total'],
            'types' => $types,
        ];
    }

    protected function formatAnimalTypeTotals(array $totals): string
    {
        return $totals['total'] . ' registrados'
            . ' (' . (int) ($totals['ACTIVO'] ?? 0) . ' activos'
            . ', ' . (int) ($totals['RESGUARDO'] ?? 0) . ' en resguardo'
            . ', ' . (int) ($totals['BAJA'] ?? 0) . ' de baja)';
    }

    protected function safeSection(callable $callback, string $fallback): string
    {
        try {
            return (string) $callback();
        } catch (\Throwable $e) {
            Log::warning('WhatsApp AI context error', [
                'error' => $e->getMessage(),
            ]);

            return $fallback;
        }
    }

    protected function dateValue($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return trim((string) $value) !== '' ? (string) $value : 'N/D';
    }

    protected function limitText($value, int $limit): string
    {
        $text = trim((string) $value);
        $text = preg_replace('/\s+/', ' ', $text);

        if ($text === '') {
            return 'N/D';
        }

        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit - 3, 'UTF-8')) . '...';
    }
}

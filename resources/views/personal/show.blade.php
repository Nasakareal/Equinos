@extends('adminlte::page')

@section('title', 'Detalle de Personal')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Detalle de Personal</h1>

        <div class="btn-group">
            @can('crear armamento')
                <a href="{{ route('armamento_asignaciones.create', ['personal_id' => $personal->id]) }}"
                   class="btn btn-primary">
                    <i class="fa-solid fa-gun"></i> Asignar arma
                </a>
            @endcan

            @can('crear incidencias')
                <a href="{{ route('incidencias.create', ['personal_id' => $personal->id]) }}"
                   class="btn btn-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i> Registrar incidencia
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Información General</h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            <strong>Nombre completo</strong>
                            <p class="text-muted">{{ $personal->nombres }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Grado</strong>
                            <p class="text-muted">{{ $personal->grado ?? '—' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Cargo</strong>
                            <p class="text-muted">{{ $personal->cargo ?? '—' }}</p>
                        </div>

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Usuario del sistema</strong>
                            <p class="text-muted">
                                @if($personal->user)
                                    {{ $personal->user->name }} <br>
                                    <small>{{ $personal->user->email }}</small>
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4">
                            <strong>No. empleado</strong>
                            <p class="text-muted">{{ $personal->no_empleado ?? '—' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Dependencia</strong>
                            <p class="text-muted">{{ $personal->dependencia ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>CUIP</strong>
                            <p class="text-muted">{{ $personal->cuip ?? '—' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>CRP</strong>
                            <p class="text-muted">{{ $personal->crp ?? '—' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Celular</strong>
                            <p class="text-muted">{{ $personal->celular ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Área de patrullaje</strong>
                            <p class="text-muted">{{ $personal->area_patrullaje ?? '—' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Responsable</strong>
                            <p class="text-muted">
                                @if($personal->es_responsable)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-3">
                            <strong>Estatus</strong>
                            <p class="text-muted">
                                @if($personal->activo)
                                    <span class="badge badge-primary">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Observaciones</strong>
                            <p class="text-muted">{{ $personal->observaciones ?: 'Sin observaciones' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('personal.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    <div class="btn-group">
                        @can('editar personal')
                            <a href="{{ route('personal.edit', $personal->id) }}" class="btn btn-success">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            @php
                $dias = [
                    0 => 'Lunes',
                    1 => 'Martes',
                    2 => 'Miércoles',
                    3 => 'Jueves',
                    4 => 'Viernes',
                    5 => 'Sábado',
                    6 => 'Domingo',
                ];

                $horariosPorDia = [];

                if (isset($horario) && isset($horario->detalles) && is_iterable($horario->detalles)) {
                    foreach ($horario->detalles as $h) {
                        $k = (int)($h->dia_semana ?? -1);
                        if (!isset($horariosPorDia[$k])) $horariosPorDia[$k] = [];
                        $horariosPorDia[$k][] = $h;
                    }
                }

                foreach ($horariosPorDia as $k => $arr) {
                    usort($arr, function ($a, $b) {
                        return strcmp((string)($a->hora_entrada ?? ''), (string)($b->hora_entrada ?? ''));
                    });
                    $horariosPorDia[$k] = $arr;
                }
            @endphp

            <div class="card card-outline card-primary">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fa-regular fa-clock"></i> Horario</h3>

                    @can('editar personal')
                        <a href="{{ route('personal.horario.edit', $personal->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa-regular fa-clock"></i> Configurar
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 140px; text-align:center;">Día</th>
                                    <th>Tramos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dias as $diaKey => $diaLabel)
                                    @php $tramos = $horariosPorDia[$diaKey] ?? []; @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold align-middle">{{ $diaLabel }}</td>
                                        <td class="align-middle">
                                            @if (count($tramos) === 0)
                                                <span class="badge badge-secondary">Sin tramos</span>
                                            @else
                                                <div class="d-flex flex-wrap" style="gap:10px;">
                                                    @foreach ($tramos as $t)
                                                        @php
                                                            $hi = substr((string)($t->hora_entrada ?? ''), 0, 5);
                                                            $hf = substr((string)($t->hora_salida ?? ''), 0, 5);
                                                            $tb = trim((string)($t->bloque ?? ''));
                                                        @endphp
                                                        <div class="border rounded px-2 py-1">
                                                            <div class="font-weight-bold">
                                                                {{ $hi }} - {{ $hf }}
                                                                @if (!empty($t->cruza_dia))
                                                                    <span class="badge badge-warning">Cruza día</span>
                                                                @endif
                                                                @if ($tb !== '')
                                                                    <span class="badge badge-info">{{ $tb }}</span>
                                                                @endif
                                                            </div>

                                                            @if (!empty($t->notas))
                                                                <div class="text-muted small">{{ $t->notas }}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa-solid fa-gun"></i> Armamento asignado</h3>
                </div>

                <div class="card-body">
                    @if(isset($armasActivas) && $armasActivas->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Estado</th>
                                        <th>Fecha asignación</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($armasActivas as $a)
                                        <tr>
                                            <td>{{ $a->weapon->tipo ?? 'N/D' }}</td>
                                            <td>{{ $a->weapon->matricula ?? 'N/D' }}</td>
                                            <td>{{ $a->weapon->estado ?? 'N/D' }}</td>
                                            <td>
                                                @php
                                                    $fa = $a->fecha_asignacion;
                                                    $faTxt = 'N/D';
                                                    if ($fa instanceof \Carbon\Carbon) $faTxt = $fa->format('d/m/Y H:i');
                                                    elseif (!empty($fa)) $faTxt = \Carbon\Carbon::parse($fa)->format('d/m/Y H:i');
                                                @endphp
                                                {{ $faTxt }}
                                            </td>
                                            <td>{{ $a->observaciones ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <span class="badge badge-secondary">Sin arma asignada</span>
                    @endif

                    @if(isset($historialArmamento) && $historialArmamento->count())
                        <hr>
                        <h5 class="mb-3"><i class="fa-solid fa-clock-rotate-left"></i> Historial</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Status</th>
                                        <th>Asignación</th>
                                        <th>Devolución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historialArmamento as $a)
                                        <tr>
                                            <td>{{ $a->weapon->tipo ?? 'N/D' }}</td>
                                            <td>{{ $a->weapon->matricula ?? 'N/D' }}</td>
                                            <td>{{ $a->status ?? 'N/D' }}</td>
                                            <td>
                                                @php
                                                    $fa = $a->fecha_asignacion;
                                                    $faTxt = 'N/D';
                                                    if ($fa instanceof \Carbon\Carbon) $faTxt = $fa->format('d/m/Y H:i');
                                                    elseif (!empty($fa)) $faTxt = \Carbon\Carbon::parse($fa)->format('d/m/Y H:i');
                                                @endphp
                                                {{ $faTxt }}
                                            </td>
                                            <td>
                                                @php
                                                    $fd = $a->fecha_devolucion;
                                                    $fdTxt = '---';
                                                    if ($fd instanceof \Carbon\Carbon) $fdTxt = $fd->format('d/m/Y H:i');
                                                    elseif (!empty($fd)) $fdTxt = \Carbon\Carbon::parse($fd)->format('d/m/Y H:i');
                                                @endphp
                                                {{ $fdTxt }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-outline card-purple">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fa-solid fa-file-pdf"></i> Puestas a disposición</h3>

                    <div class="card-tools">
                        @can('crear puestas a disposicion')
                            <a href="{{ route('puestas_disposicion.create', ['personal_id' => $personal->id]) }}" class="btn btn-purple btn-sm">
                                <i class="fa-solid fa-plus"></i> Registrar
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @if(isset($puestasDisposicion) && $puestasDisposicion->count())
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Año</th>
                                        <th>Hecho</th>
                                        <th>Archivo</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($puestasDisposicion as $pd)
                                        <tr>
                                            <td class="text-center">{{ $pd->folio ?? ('—') }}</td>
                                            <td class="text-center">{{ $pd->anio ?? '—' }}</td>
                                            <td class="text-center">{{ $pd->hecho_id ?? '—' }}</td>
                                            <td class="text-center">
                                                @if(!empty($pd->archivo_pdf))
                                                    <a href="{{ asset('storage/'.$pd->archivo_pdf) }}" target="_blank" class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                                    </a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $pd->observaciones ?? '' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @can('ver puestas a disposicion')
                                                        <a href="{{ route('puestas_disposicion.show', $pd->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('editar puestas a disposicion')
                                                        <a href="{{ route('puestas_disposicion.edit', $pd->id) }}" class="btn btn-success btn-sm" title="Editar">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    @endcan

                                                    @can('eliminar puestas a disposicion')
                                                        <form action="{{ route('puestas_disposicion.destroy', $pd->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn-pd" title="Eliminar">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <span class="badge badge-secondary">Sin puestas a disposición registradas</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop

@section('css')
    <style>
        strong { display:block; }
    </style>
@stop

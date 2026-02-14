@extends('adminlte::page')

@section('title', 'Detalle de Unidad')

@section('content_header')
    <h1>Detalle de Unidad / Patrulla</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Unidad: <strong>{{ $patrol->numero_economico }}</strong>
                    </h3>

                    <div class="card-tools">
                        @can('editar turnos')
                            <a href="{{ route('patrullas.edit', $patrol->id) }}" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        @endcan

                        <a href="{{ route('patrullas.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5>Tipo de Unidad</h5>
                            @if ($patrol->tipo == 'EQUINO')
                                <span class="badge badge-primary p-2">EQUINO</span>
                            @elseif ($patrol->tipo == 'CANINO')
                                <span class="badge badge-warning p-2">CANINO</span>
                            @elseif ($patrol->tipo == 'RAM')
                                <span class="badge badge-info p-2">RAM / PATRULLA</span>
                            @elseif ($patrol->tipo == 'LOGISTICA')
                                <span class="badge badge-dark p-2">LOGÍSTICA</span>
                            @else
                                <span class="badge badge-secondary p-2">{{ $patrol->tipo }}</span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <h5>Estado</h5>
                            @if ($patrol->estado == 'ACTIVO')
                                <span class="badge badge-success p-2">ACTIVO</span>
                            @elseif ($patrol->estado == 'TALLER')
                                <span class="badge badge-warning p-2">EN TALLER</span>
                            @elseif ($patrol->estado == 'BAJA')
                                <span class="badge badge-danger p-2">BAJA</span>
                            @else
                                <span class="badge badge-secondary p-2">{{ $patrol->estado }}</span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <h5>Observaciones</h5>
                            <p class="text-muted">
                                {{ $patrol->observaciones ?? 'Sin observaciones registradas.' }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <strong>Placas:</strong>
                            <p>{{ $patrol->placas ?? '-' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Marca:</strong>
                            <p>{{ $patrol->marca ?? '-' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Modelo:</strong>
                            <p>{{ $patrol->modelo ?? '-' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Año:</strong>
                            <p>{{ $patrol->anio ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h4 class="mt-4">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Historial de Asignaciones
                    </h4>

                    @if ($patrol->assignments && $patrol->assignments->count() > 0)

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Turno</th>
                                        <th>Servicio</th>
                                        <th>Encargado</th>
                                        <th>Agregados</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patrol->assignments as $a)

                                        @php
                                            $encargado = $a->personals->firstWhere('pivot.rol', 'ENCARGADO');
                                            $agregados = $a->personals->where('pivot.rol', 'AGREGADO');
                                        @endphp

                                        <tr>
                                            <td>{{ $a->fecha->format('d/m/Y') }}</td>
                                            <td>{{ $a->turno->nombre ?? '-' }}</td>
                                            <td>{{ $a->servicio ?? '-' }}</td>

                                            <td>
                                                {{ $encargado ? $encargado->nombres : '-' }}
                                            </td>

                                            <td>
                                                @if ($agregados->count() > 0)
                                                    <ul class="mb-0">
                                                        @foreach ($agregados as $p)
                                                            <li>{{ $p->nombres }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>{{ $a->personals->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <p class="text-muted mt-3">
                            Esta unidad aún no tiene asignaciones registradas.
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>
@stop

@section('css')
    <style>
        h5 {
            font-weight: bold;
        }
    </style>
@stop

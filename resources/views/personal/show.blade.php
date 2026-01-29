{{-- resources/views/personal/show.blade.php --}}

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
                    <h3 class="card-title">
                        Información General
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <!-- Nombre -->
                        <div class="col-md-4">
                            <strong>Nombre completo</strong>
                            <p class="text-muted">{{ $personal->nombres }}</p>
                        </div>

                        <!-- Grado -->
                        <div class="col-md-4">
                            <strong>Grado</strong>
                            <p class="text-muted">{{ $personal->grado ?? '—' }}</p>
                        </div>

                        <!-- Cargo -->
                        <div class="col-md-4">
                            <strong>Cargo</strong>
                            <p class="text-muted">{{ $personal->cargo ?? '—' }}</p>
                        </div>

                    </div>

                    <hr>

                    <div class="row">
                        <!-- Usuario -->
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

                        <!-- No empleado -->
                        <div class="col-md-4">
                            <strong>No. empleado</strong>
                            <p class="text-muted">{{ $personal->no_empleado ?? '—' }}</p>
                        </div>

                        <!-- Dependencia -->
                        <div class="col-md-4">
                            <strong>Dependencia</strong>
                            <p class="text-muted">{{ $personal->dependencia ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- CUIP -->
                        <div class="col-md-4">
                            <strong>CUIP</strong>
                            <p class="text-muted">{{ $personal->cuip ?? '—' }}</p>
                        </div>

                        <!-- CRP -->
                        <div class="col-md-4">
                            <strong>CRP</strong>
                            <p class="text-muted">{{ $personal->crp ?? '—' }}</p>
                        </div>

                        <!-- Celular -->
                        <div class="col-md-4">
                            <strong>Celular</strong>
                            <p class="text-muted">{{ $personal->celular ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Área patrullaje -->
                        <div class="col-md-6">
                            <strong>Área de patrullaje</strong>
                            <p class="text-muted">{{ $personal->area_patrullaje ?? '—' }}</p>
                        </div>

                        <!-- Responsable -->
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

                        <!-- Activo -->
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
                        <!-- Observaciones -->
                        <div class="col-md-12">
                            <strong>Observaciones</strong>
                            <p class="text-muted">
                                {{ $personal->observaciones ?: 'Sin observaciones' }}
                            </p>
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

        </div>
    </div>
@stop

@section('css')
    <style>
        strong { display:block; }
    </style>
@stop

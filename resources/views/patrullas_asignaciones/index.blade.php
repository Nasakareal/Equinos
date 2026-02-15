@extends('adminlte::page')

@section('title', 'Despliegue Diario')

@section('content_header')
    <h1>Asignaciones de Unidades</h1>
    <p class="text-muted mb-0">
        Panel dinámico de despliegue operativo (Equinos y Caninos)
    </p>
@stop

@section('content')

    <div class="row mb-3">
        <div class="col-md-12 text-right">
            @can('editar turnos')
                <a href="{{ route('patrullas_asignaciones.create') }}">
                    <i class="fa-solid fa-plus"></i> Nueva Asignación
                </a>
            @endcan
        </div>
    </div>

    <div class="row">

        @forelse($assignments as $a)

            @php
                $encargado = $a->personals->firstWhere('pivot.rol', 'ENCARGADO');
                $agregados = $a->personals->where('pivot.rol', 'AGREGADO');

                $tipo = $a->patrol->tipo ?? 'RAM';

                $badgeColor = match($tipo) {
                    'EQUINO' => 'primary',
                    'CANINO' => 'warning',
                    'LOGISTICA' => 'dark',
                    default => 'info',
                };
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-{{ $badgeColor }} shadow-sm">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-shield-dog"></i>
                            <strong>{{ $a->patrol->numero_economico }}</strong>
                        </h3>

                        <div class="card-tools">
                            <span class="badge badge-{{ $badgeColor }}">
                                {{ $tipo }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">

                        <p class="mb-1">
                            <i class="fa-solid fa-calendar-day"></i>
                            <strong>{{ $a->fecha->format('d/m/Y') }}</strong>
                            <span class="text-muted">
                                ({{ $a->turno->nombre ?? 'Sin turno' }})
                            </span>
                        </p>

                        <p class="mb-2">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="text-muted">{{ $a->zona ?? 'Zona no especificada' }}</span>
                        </p>

                        <hr>

                        <h6 class="mb-1">
                            <i class="fa-solid fa-user-shield"></i>
                            Encargado
                        </h6>

                        <p class="mb-2">
                            {{ $encargado ? $encargado->nombres : 'Sin encargado asignado' }}
                        </p>

                        <h6 class="mb-1">
                            <i class="fa-solid fa-users"></i>
                            Agregados ({{ $agregados->count() }})
                        </h6>

                        @if($agregados->count() > 0)
                            <ul class="pl-3 mb-2">
                                @foreach($agregados as $p)
                                    <li>{{ $p->nombres }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-2">Sin agregados</p>
                        @endif

                        <div class="mt-3">
                            <span class="badge badge-secondary p-2">
                                <i class="fa-solid fa-briefcase"></i>
                                {{ $a->servicio ?? 'Servicio general' }}
                            </span>
                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a href="{{ route('patrullas_asignaciones.show', $a->id) }}"
                           class="btn btn-sm btn-info">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a>

                        @can('editar turnos')
                            <a href="{{ route('patrullas_asignaciones.edit', $a->id) }}"
                               class="btn btn-sm btn-success">
                                <i class="fa-regular fa-pen-to-square"></i> Editar
                            </a>
                        @endcan

                    </div>

                </div>
            </div>

        @empty
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-circle-info"></i>
                    No hay asignaciones registradas todavía.
                </div>
            </div>
        @endforelse

    </div>

@stop

@section('css')
<style>
    .table th, .table td{
        text-align:center;
        vertical-align:middle;
    }

    .dataTables_wrapper .dataTables_paginate{
        padding-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link{
        background: rgba(0,0,0,.22) !important;
        color: rgba(234,240,255,.92) !important;
        border: 1px solid rgba(255,255,255,.14) !important;
        border-radius: 12px !important;
        margin: 0 4px !important;
        padding: 10px 14px !important;
        font-weight: 900 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link:hover{
        background: rgba(45,168,255,.18) !important;
        border-color: rgba(45,168,255,.45) !important;
        color: rgba(234,240,255,.98) !important;
        transform: translateY(-1px);
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link{
        background: linear-gradient(135deg, rgba(45,168,255,.35), rgba(124,92,255,.30)) !important;
        border-color: rgba(45,168,255,.60) !important;
        color: rgba(234,240,255,.98) !important;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link{
        background: rgba(0,0,0,.14) !important;
        border-color: rgba(255,255,255,.10) !important;
        color: rgba(234,240,255,.55) !important;
        opacity: .55 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link:focus{
        box-shadow: 0 0 0 3px rgba(45,168,255,.18) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button a{
        background: rgba(0,0,0,.22) !important;
        color: rgba(234,240,255,.92) !important;
    }
</style>
@stop

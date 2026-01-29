{{-- resources/views/armamento/show.blade.php --}}

@extends('adminlte::page')

@section('title', 'Detalle de Armamento')

@section('content_header')
    <h1>Detalle de Armamento</h1>
@stop

@section('content')
<div class="row">
    <!-- Datos del arma -->
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Información del Arma</h3>

                <div class="card-tools">
                    @can('editar armamento')
                        <a href="{{ route('armamento.edit', $weapon->id) }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Tipo</th>
                            <td>{{ $weapon->tipo }}</td>
                        </tr>
                        <tr>
                            <th>Matrícula</th>
                            <td>{{ $weapon->matricula }}</td>
                        </tr>
                        <tr>
                            <th>Marca / Modelo</th>
                            <td>{{ $weapon->marca_modelo ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @if($weapon->estado === 'ACTIVA')
                                    <span class="badge badge-success">ACTIVA</span>
                                @elseif($weapon->estado === 'INACTIVA')
                                    <span class="badge badge-secondary">INACTIVA</span>
                                @elseif($weapon->estado === 'BAJA')
                                    <span class="badge badge-danger">BAJA</span>
                                @else
                                    <span class="badge badge-dark">{{ $weapon->estado }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Observaciones</th>
                            <td style="white-space: pre-wrap;">{{ $weapon->observaciones ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de registro</th>
                            <td>{{ optional($weapon->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última actualización</th>
                            <td>{{ optional($weapon->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="{{ route('armamento.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <!-- Historial de asignaciones -->
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Historial de Asignaciones</h3>
            </div>

            <div class="card-body">
                @if ($weapon->assignments->isEmpty())
                    <div class="alert alert-secondary text-center mb-0">
                        No existen asignaciones registradas para este armamento.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Personal</th>
                                    <th>Fecha asignación</th>
                                    <th>Fecha devolución</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($weapon->assignments as $asig)
                                    <tr>
                                        <td>
                                            {{ $asig->personal->nombres ?? '—' }}
                                        </td>
                                        <td>
                                            {{ optional($asig->fecha_asignacion)->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td>
                                            {{ optional($asig->fecha_devolucion)->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td>
                                            @if(in_array($asig->status, ['ASIGNADA','ASIGNADO']))
                                                <span class="badge badge-success">{{ $asig->status }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $asig->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table.table-bordered,
    .table.table-bordered td,
    .table.table-bordered th{
        border-color: rgba(255,255,255,.18) !important;
    }

    .table thead th{
        background: rgba(255,255,255,.10) !important;
        color: #ffffff !important;
        white-space: nowrap;
        font-weight: 700;
    }

    .table tbody td,
    .table tbody th{
        background: rgba(0,0,0,.10) !important;
        color: #ffffff !important;
        vertical-align: middle;
    }

    .table.table-sm.table-bordered tbody th{
        background: rgba(255,255,255,.08) !important;
        color: rgba(255,255,255,.85) !important;
        width: 35%;
        white-space: nowrap;
    }

    td[style*="white-space: pre-wrap"],
    td[style*="white-space: normal"]{
        color: #ffffff !important;
    }

    .badge{
        padding: .35em .6em;
        font-weight: 700;
        letter-spacing: .3px;
    }
</style>
@stop


@extends('adminlte::page')

@section('title', 'Detalle de Asignación de Armamento')

@section('content_header')
    <h1>Detalle de Asignación de Armamento</h1>
@stop

@section('content')
<div class="row">

    <!-- Información de la asignación -->
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos de la Asignación</h3>

                <div class="card-tools">
                    @can('editar armamento')
                        <a href="{{ route('armamento_asignaciones.edit', $weapon_assignment->id) }}"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Estatus</th>
                            <td>
                                @if($weapon_assignment->status === 'ASIGNADA')
                                    <span class="badge badge-success">ASIGNADA</span>
                                @elseif($weapon_assignment->status === 'DEVUELTA')
                                    <span class="badge badge-secondary">DEVUELTA</span>
                                @elseif($weapon_assignment->status === 'CANCELADA')
                                    <span class="badge badge-danger">CANCELADA</span>
                                @else
                                    <span class="badge badge-dark">{{ $weapon_assignment->status }}</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de asignación</th>
                            <td>{{ optional($weapon_assignment->fecha_asignacion)->format('d/m/Y') ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Fecha de devolución</th>
                            <td>{{ optional($weapon_assignment->fecha_devolucion)->format('d/m/Y') ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Observaciones</th>
                            <td style="white-space: pre-wrap;">
                                {{ $weapon_assignment->observaciones ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de registro</th>
                            <td>{{ optional($weapon_assignment->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>

                        <tr>
                            <th>Última actualización</th>
                            <td>{{ optional($weapon_assignment->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="{{ route('armamento_asignaciones.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <!-- Información del arma y personal -->
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Arma y Personal</h3>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Personal</th>
                            <td>{{ $weapon_assignment->personal->nombres ?? '—' }}</td>
                        </tr>

                        <tr>
                            <th>Arma</th>
                            <td>
                                {{ $weapon_assignment->weapon->tipo ?? '—' }}
                                — {{ $weapon_assignment->weapon->matricula ?? '—' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Marca / Modelo</th>
                            <td>{{ $weapon_assignment->weapon->marca_modelo ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Estado actual del arma</th>
                            <td>
                                @if($weapon_assignment->weapon->estado === 'ACTIVA')
                                    <span class="badge badge-success">ACTIVA</span>
                                @elseif($weapon_assignment->weapon->estado === 'INACTIVA')
                                    <span class="badge badge-secondary">INACTIVA</span>
                                @elseif($weapon_assignment->weapon->estado === 'BAJA')
                                    <span class="badge badge-danger">BAJA</span>
                                @else
                                    <span class="badge badge-dark">{{ $weapon_assignment->weapon->estado }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
    .table th {
        background-color: rgba(255,255,255,.08);
        color: #ffffff;
        white-space: nowrap;
    }

    .table td {
        color: #ffffff;
        vertical-align: middle;
    }
</style>
@stop

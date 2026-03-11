@extends('adminlte::page')

@section('title', 'Detalle de Equinoterapia')

@section('content_header')
    <h1>Detalle de Equinoterapia</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-pink">

            <div class="card-header">
                <h3 class="card-title">
                    Reporte del día {{ \Carbon\Carbon::parse($equinoterapia->fecha)->format('d/m/Y') }}
                </h3>

                <div class="card-tools">

                    @can('ver animales')
                        <a href="{{ route('equinoterapias.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    @endcan

                    @can('editar animales')
                        <a href="{{ route('equinoterapias.edit', $equinoterapia->id) }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                    @can('editar animales')
                        <a href="{{ route('equinoterapias.whatsapp', $equinoterapia->id) }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="fa-brands fa-whatsapp"></i> Compartir
                        </a>
                    @endcan

                </div>
            </div>

            <div class="card-body">

                <ul class="nav nav-tabs" id="equinoterapiaTabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="tab-resumen-tab" data-toggle="tab" href="#tab-resumen" role="tab">
                            <i class="fa-solid fa-chart-column"></i> Resumen
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-registros-tab" data-toggle="tab" href="#tab-registros" role="tab">
                            <i class="fa-solid fa-users"></i> Registros
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-diagnosticos-tab" data-toggle="tab" href="#tab-diagnosticos" role="tab">
                            <i class="fa-solid fa-notes-medical"></i> Diagnósticos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-whatsapp-tab" data-toggle="tab" href="#tab-whatsapp" role="tab">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    </li>

                </ul>

                <div class="tab-content pt-3">

                    <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">

                        <div class="row">

                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box bg-gradient-primary">
                                    <span class="info-box-icon"><i class="fa-solid fa-horse"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Terapias</span>
                                        <span class="info-box-number">{{ $totales['realizadas'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box bg-gradient-danger">
                                    <span class="info-box-icon"><i class="fa-solid fa-user-xmark"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Inasistencias</span>
                                        <span class="info-box-number">{{ $totales['inasistencias'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box bg-gradient-pink">
                                    <span class="info-box-icon"><i class="fa-solid fa-child-dress"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Niñas</span>
                                        <span class="info-box-number">{{ $totales['ninas'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box bg-gradient-info">
                                    <span class="info-box-icon"><i class="fa-solid fa-child"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Niños</span>
                                        <span class="info-box-number">{{ $totales['ninos'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="info-box bg-gradient-warning">
                                    <span class="info-box-icon"><i class="fa-solid fa-notes-medical"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Valoraciones</span>
                                        <span class="info-box-number">{{ $totales['valoraciones'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fa-solid fa-user-shield"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Personal</span>
                                        <span class="info-box-number">{{ $totales['personal'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 col-12">
                                <div class="info-box bg-gradient-secondary">
                                    <span class="info-box-icon"><i class="fa-solid fa-paw"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Equinos</span>
                                        <span class="info-box-number">{{ $totales['equinos'] }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Fecha</th>
                                            <td>{{ \Carbon\Carbon::parse($equinoterapia->fecha)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Personal</th>
                                            <td>{{ $equinoterapia->personal }}</td>
                                        </tr>
                                        <tr>
                                            <th>Equinos</th>
                                            <td>{{ $equinoterapia->equinos }}</td>
                                        </tr>
                                        <tr>
                                            <th>Valoraciones capturadas</th>
                                            <td>{{ $equinoterapia->valoraciones }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total de registros</th>
                                            <td>{{ $equinoterapia->registros->count() }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Actividades del área</th>
                                            <td>{{ $equinoterapia->actividades_area ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Observaciones</th>
                                            <td>{{ $equinoterapia->observaciones ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Creado</th>
                                            <td>{{ $equinoterapia->created_at ? $equinoterapia->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Actualizado</th>
                                            <td>{{ $equinoterapia->updated_at ? $equinoterapia->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-outline card-pink mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">Inasistencias del día</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th>Sexo</th>
                                                        <th>Diagnóstico</th>
                                                        <th>Motivo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($inasistencias as $index => $inasistencia)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $inasistencia->nombre_completo }}</td>
                                                            <td>{{ $inasistencia->sexo }}</td>
                                                            <td>{{ $inasistencia->diagnostico ?: '-' }}</td>
                                                            <td>{{ $inasistencia->motivo_inasistencia ?: '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">Sin inasistencias registradas</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-registros" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Listado de registros</h5>

                            @can('editar animales')
                                <a href="{{ route('equinoterapias.edit', $equinoterapia->id) }}" class="btn btn-pink btn-sm">
                                    <i class="fa-regular fa-pen-to-square"></i> Editar registros
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre completo</th>
                                        <th>Sexo</th>
                                        <th>Diagnóstico</th>
                                        <th>Asistencia</th>
                                        <th>Valoración</th>
                                        <th>Motivo de inasistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equinoterapia->registros as $index => $registro)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $registro->nombre_completo }}</td>
                                            <td>
                                                @if($registro->sexo == 'NIÑA')
                                                    <span class="badge badge-pink">NIÑA</span>
                                                @else
                                                    <span class="badge badge-info">NIÑO</span>
                                                @endif
                                            </td>
                                            <td>{{ $registro->diagnostico ?: '-' }}</td>
                                            <td>
                                                @if($registro->estatus_asistencia == 'ASISTIO')
                                                    <span class="badge badge-success">ASISTIÓ</span>
                                                @else
                                                    <span class="badge badge-danger">INASISTIÓ</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($registro->es_valoracion)
                                                    <span class="badge badge-warning">SÍ</span>
                                                @else
                                                    <span class="badge badge-secondary">NO</span>
                                                @endif
                                            </td>
                                            <td>{{ $registro->motivo_inasistencia ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Sin registros capturados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-diagnosticos" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Resumen de diagnósticos</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Diagnóstico</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($diagnosticos as $diagnostico => $cantidad)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $diagnostico }}</td>
                                            <td>{{ $cantidad }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Sin diagnósticos registrados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-whatsapp" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-outline card-success mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">Mensaje listo para WhatsApp</h3>

                                        <div class="card-tools">
                                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-sm">
                                                <i class="fa-brands fa-whatsapp"></i> Abrir en WhatsApp
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <textarea class="form-control" rows="18" readonly>{{ $mensajeWhatsapp }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@stop

@section('css')
<style>
input.form-control,
textarea.form-control,
select.form-control {
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    background-color: #25364a !important;
    color: #ffffff !important;
    border-color: #e83e8c !important;
    box-shadow: 0 0 0 0.2rem rgba(232, 62, 140, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

::placeholder {
    color: #b8c7ce !important;
    opacity: 1;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

.btn-pink {
    background: linear-gradient(135deg, #e83e8c, #c2185b) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 8px 18px;
    box-shadow: 0 4px 10px rgba(232, 62, 140, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-pink:hover {
    background: linear-gradient(135deg, #d63384, #ad1457) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(232, 62, 140, 0.45);
}

.btn-pink:focus,
.btn-pink:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(232, 62, 140, 0.4) !important;
}

.card-outline.card-pink {
    border-top: 3px solid #e83e8c;
}

.badge-pink {
    background-color: #e83e8c;
    color: #fff;
}
</style>
@stop

@section('js')
<script>
@if(session('success'))
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 3000
});
@endif
</script>
@stop

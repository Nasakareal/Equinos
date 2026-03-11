@extends('adminlte::page')

@section('title', 'Equinoterapias')

@section('content_header')
    <h1>Equinoterapias</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-pink">

            <div class="card-header">
                <h3 class="card-title">Listado de reportes de equinoterapia</h3>

                <div class="card-tools d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('equinoterapias.index') }}" class="d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control form-control-sm">
                        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control form-control-sm">
                        <input type="date" name="semana_inicio" value="{{ request('semana_inicio', $inicioSemana->format('Y-m-d')) }}" class="form-control form-control-sm">
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('equinoterapias.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-rotate-left"></i> Limpiar
                        </a>
                    </form>

                    @can('editar animales')
                        <a href="{{ route('equinoterapias.create') }}" class="btn btn-pink btn-sm">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card card-outline card-info mb-0">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Resumen semanal del {{ $inicioSemana->format('d/m/Y') }} al {{ $finSemana->format('d/m/Y') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>{{ $totalesSemana['realizadas'] }}</h3>
                                                <p>Terapias</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-horse"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{ $totalesSemana['inasistencias'] }}</h3>
                                                <p>Inasistencias</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-user-xmark"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-pink">
                                            <div class="inner">
                                                <h3>{{ $totalesSemana['ninas'] }}</h3>
                                                <p>Niñas</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-child-dress"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{ $totalesSemana['ninos'] }}</h3>
                                                <p>Niños</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-child"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{ $totalesSemana['valoraciones'] }}</h3>
                                                <p>Valoraciones</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-notes-medical"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{ $reportes->count() }}</h3>
                                                <p>Reportes listados</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="col-md-6 col-sm-6 col-12 mb-2">
                                        <div class="p-3 rounded" style="background: #f4f6f9; border: 1px solid #dee2e6;">
                                            <strong>Personal acumulado:</strong> {{ $totalesSemana['personal'] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 mb-2">
                                        <div class="p-3 rounded" style="background: #f4f6f9; border: 1px solid #dee2e6;">
                                            <strong>Equinos acumulados:</strong> {{ $totalesSemana['equinos'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="equinoterapias" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Registros</th>
                                <th>Valoraciones</th>
                                <th>Personal</th>
                                <th>Equinos</th>
                                <th>Actividades del área</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes as $index => $reporte)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $reporte->registros_count }}</td>
                                    <td>{{ $reporte->valoraciones }}</td>
                                    <td>{{ $reporte->personal }}</td>
                                    <td>{{ $reporte->equinos }}</td>
                                    <td>{{ $reporte->actividades_area ?: '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('equinoterapias.show', $reporte->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            @can('editar animales')
                                                <a href="{{ route('equinoterapias.edit', $reporte->id) }}" class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>

                                                <a href="{{ route('equinoterapias.whatsapp', $reporte->id) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fa-brands fa-whatsapp"></i>
                                                </a>
                                            @endcan

                                            @can('editar animales')
                                                <form action="{{ route('equinoterapias.destroy', $reporte->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
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

            </div>

        </div>
    </div>
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

    .small-box .icon i{
        font-size: 50px;
        top: 12px;
    }

    .bg-pink{
        background-color: #e83e8c !important;
        color: #fff !important;
    }

    .btn-pink{
        background-color: #e83e8c !important;
        border-color: #e83e8c !important;
        color: #fff !important;
    }

    .btn-pink:hover{
        background-color: #d63384 !important;
        border-color: #d63384 !important;
        color: #fff !important;
    }

    .card-outline.card-pink{
        border-top: 3px solid #e83e8c;
    }
</style>
@stop

@section('js')
<script>
$(function () {
    if (!$.fn.DataTable.isDataTable('#equinoterapias')) {
        $('#equinoterapias').DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ total registros)",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            autoWidth: false,
            scrollX: true,
            ordering: false
        });
    }
});

@if(session('success'))
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 3000
});
@endif

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');

    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede revertir',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@stop

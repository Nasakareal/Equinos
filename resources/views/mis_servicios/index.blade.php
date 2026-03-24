@extends('adminlte::page')

@section('title', 'Mis Servicios')

@section('content_header')
    <h1>Mis Servicios</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Servicios y apoyos asignados / disponibles para captura</h3>
                    <div class="card-tools">
                        <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Volver a administración
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mis_servicios" class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Fecha</center></th>
                                    <th><center>Hora</center></th>
                                    <th><center>Categoría</center></th>
                                    <th><center>Tipo de servicio</center></th>
                                    <th><center>Asunto</center></th>
                                    <th><center>Municipio</center></th>
                                    <th><center>Lugar</center></th>
                                    <th><center>Reportes</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($servicios as $index => $servicio)
                                    <tr>
                                        <td style="text-align: center">{{ $index + 1 }}</td>
                                        <td>{{ optional($servicio->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}</td>
                                        <td>{{ $servicio->categoria_registro ?? '-' }}</td>
                                        <td>{{ $servicio->tipo_servicio ?? '-' }}</td>
                                        <td>{{ $servicio->asunto ?? '-' }}</td>
                                        <td>{{ $servicio->municipio ?? '-' }}</td>
                                        <td>{{ $servicio->lugar ?? '-' }}</td>
                                        <td style="text-align: center">{{ $servicio->reportes->count() }}</td>
                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">

                                                @can('ver reportes de servicios')
                                                    <a href="{{ route('mis_servicios.show', $servicio->id) }}" class="btn btn-info btn-sm" title="Ver panel operativo">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('crear reportes de servicios')
                                                    <a href="{{ route('mis_servicios.reportes.create', $servicio->id) }}" class="btn btn-primary btn-sm" title="Nuevo reporte">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </a>
                                                @endcan

                                                @can('ver reportes de servicios')
                                                    <a href="{{ route('mis_servicios.reportes.index', $servicio->id) }}" class="btn btn-secondary btn-sm" title="Ver reportes">
                                                        <i class="fa-solid fa-file-lines"></i>
                                                    </a>
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
</style>
@stop

@section('js')
<script>
    $(function () {
        const dt = $('#mis_servicios').DataTable({
            "pageLength": 10,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(Filtrado de _MAX_ total registros)",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "scrollX": true,
            "deferRender": true
        });

        setTimeout(function () {
            dt.columns.adjust().responsive.recalc();
        }, 150);
    });

    @if (session('success'))
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 12000
        });
    @endif
</script>
@stop

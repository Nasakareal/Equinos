@extends('adminlte::page')

@section('title', 'Listado de Servicios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Servicios, Apoyos y Memorándums</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Control operativo del Agrupamiento de Equinos y Caninos
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            @can('crear servicios')
                <a href="{{ url('/servicios/create') }}" class="btn btn-primary shadow-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Registrar Servicio
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    @php
        $totalServicios = $servicios->count();
        $cumplidos = $servicios->where('cumplio', true)->count();
        $noCumplidos = $servicios->where('cumplio', false)->count();

        $soloServicios = $servicios->where('tipo_registro', 'SERVICIO')->count();
        $soloApoyos = $servicios->where('tipo_registro', 'APOYO')->count();
        $soloMemorandums = $servicios->where('tipo_registro', 'MEMORANDUM')->count();

        function badgeTipoRegistro($tipo) {
            return match (strtoupper((string)$tipo)) {
                'SERVICIO' => 'badge badge-primary',
                'APOYO' => 'badge badge-success',
                'MEMORANDUM' => 'badge badge-warning',
                default => 'badge badge-secondary',
            };
        }

        function badgeTipoServicio($tipo) {
            return match (strtoupper((string)$tipo)) {
                'SEGURIDAD' => 'badge badge-info',
                'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
                'BUSQUEDA' => 'badge badge-danger',
                'DESFILES' => 'badge badge-purple',
                'PROXIMIDAD SOCIAL' => 'badge badge-success',
                'ACTOS CIVICOS' => 'badge badge-warning',
                default => 'badge badge-secondary',
            };
        }
    @endphp

    <div class="row mb-3">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $totalServicios }}</h3>
                    <p>Total registrados</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $cumplidos }}</h3>
                    <p>Cumplidos</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $noCumplidos }}</h3>
                    <p>No cumplidos</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-indigo shadow-sm">
                <div class="inner">
                    <h3>{{ $soloServicios }}</h3>
                    <p>Servicios</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-teal shadow-sm">
                <div class="inner">
                    <h3>{{ $soloApoyos }}</h3>
                    <p>Apoyos</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-handshake-angle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ $soloMemorandums }}</h3>
                    <p>Memorándums</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Listado general</h3>

            <div class="card-tools d-flex flex-wrap align-items-center" style="gap: .5rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm filtro-registro" data-tipo="">
                    Todos
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm filtro-registro" data-tipo="SERVICIO">
                    Servicios
                </button>
                <button type="button" class="btn btn-outline-success btn-sm filtro-registro" data-tipo="APOYO">
                    Apoyos
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm filtro-registro" data-tipo="MEMORANDUM">
                    Memorándums
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="servicios" class="table table-striped table-bordered table-hover table-sm w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Cumplió</th>
                            <th>Personal</th>
                            <th>Canino</th>
                            <th>Equino</th>
                            <th>Patrulla</th>
                            <th>Creó</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($servicios as $index => $servicio)
                            @php
                                $patrullaTexto = '-';

                                if (!empty($servicio->patrulla)) {
                                    $patrullaTexto =
                                        $servicio->patrulla->nombre ??
                                        $servicio->patrulla->numero ??
                                        $servicio->patrulla->placas ??
                                        ('ID ' . $servicio->patrulla->id);
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>


                                <td>
                                    <span class="{{ badgeTipoServicio($servicio->tipo_servicio) }} badge-pill px-3 py-2">
                                        {{ $servicio->tipo_servicio }}
                                    </span>
                                    @if ($servicio->tipo_busqueda)
                                        <div class="mt-1">
                                            <span class="badge badge-light border text-dark">
                                                {{ $servicio->tipo_busqueda }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td>{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}</td>

                                <td>{{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}</td>

                                <td>
                                    @if ($servicio->cumplio)
                                        <span class="badge badge-success badge-pill px-3 py-2">
                                            <i class="fa-solid fa-check mr-1"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-danger badge-pill px-3 py-2">
                                            <i class="fa-solid fa-xmark mr-1"></i> No
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $servicio->personal->nombres ?? '-' }}</td>
                                <td>{{ $servicio->canino->nombre ?? '-' }}</td>
                                <td>{{ $servicio->equino->nombre ?? '-' }}</td>
                                <td>{{ $patrullaTexto }}</td>
                                <td>{{ $servicio->user->name ?? '-' }}</td>

                                <td>
                                    <div class="btn-group" role="group">
                                        @can('ver servicios')
                                            <a href="{{ url('/servicios/' . $servicio->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('editar servicios')
                                            <a href="{{ url('/servicios/' . $servicio->id . '/edit') }}" class="btn btn-success btn-sm" title="Editar">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                        @endcan

                                        @can('eliminar servicios')
                                            <form action="{{ url('/servicios/' . $servicio->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm delete-btn" title="Eliminar">
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
@stop

@section('css')
<style>
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .badge-pill {
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .small-box {
        border-radius: 14px;
        overflow: hidden;
    }

    .small-box .icon {
        top: 10px;
        right: 12px;
        font-size: 52px;
        opacity: .18;
    }

    .card {
        border-radius: 14px;
    }

    .btn {
        border-radius: 10px;
    }

    .table thead th {
        white-space: nowrap;
    }

    .table tbody td {
        white-space: nowrap;
    }

    .table tbody td:nth-child(12) {
        white-space: normal !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link {
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

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link:hover {
        background: rgba(45,168,255,.18) !important;
        border-color: rgba(45,168,255,.45) !important;
        color: rgba(234,240,255,.98) !important;
        transform: translateY(-1px);
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, rgba(45,168,255,.35), rgba(124,92,255,.30)) !important;
        border-color: rgba(45,168,255,.60) !important;
        color: rgba(234,240,255,.98) !important;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link {
        background: rgba(0,0,0,.14) !important;
        border-color: rgba(255,255,255,.10) !important;
        color: rgba(234,240,255,.55) !important;
        opacity: .55 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 3px rgba(45,168,255,.18) !important;
    }

    .filtro-registro.active-filter {
        box-shadow: 0 0 0 3px rgba(0,123,255,.15);
        transform: translateY(-1px);
    }

    .bg-indigo {
        background-color: #6610f2 !important;
        color: #fff !important;
    }

    .bg-teal {
        background-color: #20c997 !important;
        color: #fff !important;
    }

    .badge-purple {
        background-color: #6f42c1;
        color: #fff;
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        let tabla = $('#servicios').DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ total registros)",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscador:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            scrollX: true,
            deferRender: true,
            order: [[3, 'desc'], [4, 'desc']]
        });

        setTimeout(function () {
            tabla.columns.adjust().responsive.recalc();
        }, 150);

        $('.filtro-registro').on('click', function () {
            let tipo = $(this).data('tipo');
            $('.filtro-registro').removeClass('active-filter');
            $(this).addClass('active-filter');
            tabla.column(1).search(tipo).draw();
        });
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

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Estás seguro de eliminar este registro?',
            text: '¡No podrás revertir esta acción!',
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

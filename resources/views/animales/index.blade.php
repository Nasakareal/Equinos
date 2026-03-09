@extends('adminlte::page')

@section('title', 'Unidad Canina y Equina')

@section('content_header')
    <h1>Unidad Canina y Equina</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">Listado de Animales</h3>

                <div class="card-tools">
                    <form method="GET" class="d-inline-block mr-2">
                        <select name="tipo" class="form-control form-control-sm d-inline-block" style="width:140px">
                            <option value="">Todos</option>
                            <option value="EQUINO" {{ request('tipo') == 'EQUINO' ? 'selected' : '' }}>Equinos</option>
                            <option value="CANINO" {{ request('tipo') == 'CANINO' ? 'selected' : '' }}>Caninos</option>
                        </select>
                    </form>

                    @can('crear animales')
                        <a href="{{ url('/animales/create') }}" class="btn btn-purple btn-sm">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="animals" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th>Edad</th>
                                <th>Especialidad</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($animals as $index => $animal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($animal->tipo == 'EQUINO')
                                            <span class="badge badge-info">Equino</span>
                                        @else
                                            <span class="badge badge-success">Canino</span>
                                        @endif
                                    </td>
                                    <td>{{ $animal->nombre }}</td>
                                    <td>{{ $animal->raza ?? '-' }}</td>
                                    <td>{{ $animal->edad_calculada ?? '-' }}</td>
                                    <td>{{ $animal->especialidad ?? '-' }}</td>
                                    <td>
                                        @if($animal->estatus == 'ACTIVO')
                                            <span class="badge badge-success">Activo</span>
                                        @elseif($animal->estatus == 'BAJA')
                                            <span class="badge badge-danger">Baja</span>
                                        @else
                                            <span class="badge badge-warning">Resguardo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @can('ver animales')
                                                <a href="{{ url('/animales/'.$animal->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('editar animales')
                                                <a href="{{ url('/animales/'.$animal->id.'/edit') }}" class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar animales')
                                                <form action="{{ url('/animales/'.$animal->id) }}" method="POST" style="display:inline-block;">
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
</style>
@stop

@section('js')
<script>
$(function () {
    if (!$.fn.DataTable.isDataTable('#animals')) {
        $('#animals').DataTable({
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
            scrollX: true
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

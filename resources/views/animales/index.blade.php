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
                            <option value="EQUINO" {{ request('tipo')=='EQUINO'?'selected':'' }}>Equinos</option>
                            <option value="CANINO" {{ request('tipo')=='CANINO'?'selected':'' }}>Caninos</option>
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
                                                <a href="{{ url('/animales/'.$animal->id) }}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('editar animales')
                                                <a href="{{ url('/animales/'.$animal->id.'/edit') }}"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar animales')
                                                <form action="{{ url('/animales/'.$animal->id) }}"
                                                      method="POST"
                                                      style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn">
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

/* Centrar contenido de tabla */
.table th, .table td{
    text-align:center;
    vertical-align:middle;
}

/* Botón Agregar más visible */
.btn-purple{
    background: linear-gradient(135deg, #6f42c1, #4e2a8e);
    border: none;
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(111, 66, 193, 0.35);
    transition: all 0.3s ease-in-out;
}

.btn-purple:hover{
    background: linear-gradient(135deg, #5a32a3, #3d1f73);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(111, 66, 193, 0.45);
}

.btn-purple:focus,
.btn-purple:active{
    outline: none;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.4);
}

</style>
@stop


@section('js')

<script>
$(function () {

    $('#animals').DataTable({
        "pageLength": 10,
        "language": {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(Filtrado de _MAX_ total registros)",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "responsive": true,
        "autoWidth": false,
        "scrollX": true
    });

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
        text: "Esta acción no se puede revertir",
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

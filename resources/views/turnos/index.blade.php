{{-- resources/views/turnos/index.blade.php --}}

@extends('adminlte::page')

@section('title', 'Listado de Turnos')

@section('content_header')
    <h1>Listado de Turnos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Turnos Registrados</h3>
                    <div class="card-tools">
                        @can('crear turnos')
                            <a href="{{ route('turnos.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Agregar Turno
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="turnos" class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Clave</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Descripción</center></th>
                                    <th><center>Estado</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($turnos as $index => $turno)
                                    <tr>
                                        <td style="text-align: center">{{ $index + 1 }}</td>
                                        <td>{{ $turno->clave ?? '-' }}</td>
                                        <td>{{ $turno->nombre ?? '-' }}</td>
                                        <td>{{ $turno->descripcion ?? '-' }}</td>

                                        <td>
                                            @if(!empty($turno->activo) && (int)$turno->activo === 1)
                                                <span class="badge badge-success">ACTIVO</span>
                                            @else
                                                <span class="badge badge-secondary">INACTIVO</span>
                                            @endif
                                        </td>

                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">

                                                @can('ver turnos')
                                                    <a href="{{ route('turnos.show', $turno->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('editar turnos')
                                                    <a href="{{ route('turnos.edit', $turno->id) }}" class="btn btn-success btn-sm" title="Editar">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                @endcan

                                                @can('eliminar turnos')
                                                    <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" style="display:inline-block;">
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
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td{
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dataTables_wrapper{
            width: 100%;
        }

        table.dataTable{
            width: 100% !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(function () {
            const dt = $('#turnos').DataTable({
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

        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();

            let form = $(this).closest('form');

            Swal.fire({
                title: '¿Estás seguro de eliminar este registro?',
                text: "¡No podrás revertir esta acción!",
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

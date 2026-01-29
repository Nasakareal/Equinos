{{-- resources/views/personal/index.blade.php --}}

@extends('adminlte::page')

@section('title', 'Listado de Personal')

@section('content_header')
    <h1>Listado de Personal</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Personal Registrado</h3>
                    <div class="card-tools">
                        @can('crear personal')
                            <a href="{{ url('/personal/create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Agregar Personal
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="personals" class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Grado</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Dependencia</center></th>
                                    <th><center>CUIP</center></th>
                                    <th><center>Celular</center></th>
                                    <th><center>CRP</center></th>
                                    <th><center>Cargo</center></th>
                                    <th><center>Responsable</center></th>
                                    <th><center>Estado</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($personals as $index => $personal)
                                    <tr>
                                        <td style="text-align: center">{{ $index + 1 }}</td>
                                        <td>{{ $personal->grado ?? '-' }}</td>
                                        <td>{{ $personal->nombres }}</td>
                                        <td>{{ $personal->dependencia ?? '-' }}</td>
                                        <td>{{ $personal->cuip ?? '-' }}</td>
                                        <td>{{ $personal->celular ?? '-' }}</td>
                                        <td>{{ $personal->crp ?? '-' }}</td>
                                        <td>{{ $personal->cargo ?? '-' }}</td>
                                        <td>
                                            @if(!empty($personal->es_responsable) && (int)$personal->es_responsable === 1)
                                                <span class="badge badge-success">Sí</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($personal->activo) && (int)$personal->activo === 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">

                                                @can('ver personal')
                                                    <a href="{{ url('/personal/' . $personal->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('editar personal')
                                                    <a href="{{ url('/personal/' . $personal->id . '/edit') }}" class="btn btn-success btn-sm" title="Editar">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                @endcan

                                                @can('eliminar personal')
                                                    <form action="{{ url('/personal/' . $personal->id) }}" method="POST" style="display:inline-block;">
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
            const dt = $('#personals').DataTable({
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

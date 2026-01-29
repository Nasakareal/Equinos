@extends('adminlte::page')

@section('title', 'Incidencias')

@section('content_header')
    <h1>Incidencias</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Listado de Incidencias</h3>

                <div class="card-tools">
                    @can('crear incidencias')
                        <a href="{{ route('incidencias.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Nueva Incidencia
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="incidencias" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Personal</th>
                                <th>Tipo</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Comentario</th>
                                <th>Registró</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($incidencias as $index => $inc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td style="white-space: normal; min-width: 240px;">
                                        {{ $inc->personal->nombres ?? '—' }}
                                    </td>

                                    <td>{{ $inc->type->nombre ?? '—' }}</td>

                                    <td>{{ optional($inc->fecha_inicio)->format('d/m/Y') }}</td>

                                    <td>
                                        @if(!empty($inc->fecha_fin))
                                            {{ optional($inc->fecha_fin)->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td style="white-space: normal; min-width: 320px; text-align:left;">
                                        {{ $inc->comentario ?? '-' }}
                                    </td>

                                    <td>
                                        @if(!empty($inc->registrado_por))
                                            {{ $inc->registrado_por }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">

                                            @can('ver incidencias')
                                                <a href="{{ route('incidencias.show', $inc->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('editar incidencias')
                                                <a href="{{ route('incidencias.edit', $inc->id) }}"
                                                   class="btn btn-success btn-sm"
                                                   title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar incidencias')
                                                <form action="{{ route('incidencias.destroy', $inc->id) }}"
                                                      method="POST"
                                                      style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn"
                                                            title="Eliminar">
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
</style>
@stop

@section('js')
<script>
    $(function () {
        const dt = $('#incidencias').DataTable({
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
            deferRender: true
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
            title: '¿Eliminar incidencia?',
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

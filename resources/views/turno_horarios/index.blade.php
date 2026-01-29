{{-- resources/views/turno_horarios/index.blade.php --}}

@extends('adminlte::page')

@section('title', 'Horarios de Turnos')

@section('content_header')
    <h1>Horarios de Turnos</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Listado de Horarios</h3>

                <div class="card-tools">
                    @can('crear turnos')
                        <a href="{{ route('turno_horarios.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Agregar Horario
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="turno_horarios" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th><center>#</center></th>
                                <th><center>Turno</center></th>
                                <th><center>Entrada</center></th>
                                <th><center>Salida</center></th>
                                <th><center>Tolerancia (min)</center></th>
                                <th><center>Cruza día</center></th>
                                <th><center>Notas</center></th>
                                <th><center>Acciones</center></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($turno_horarios as $index => $horario)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $horario->turno->nombre ?? '-' }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $horario->turno->clave ?? '' }}
                                        </small>
                                    </td>

                                    <td>{{ $horario->hora_entrada ?? '-' }}</td>
                                    <td>{{ $horario->hora_salida ?? '-' }}</td>
                                    <td>{{ $horario->min_tolerancia }}</td>

                                    <td>
                                        @if((int)$horario->cruza_dia === 1)
                                            <span class="badge badge-warning">Sí</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>

                                    <td>{{ $horario->notas ?? '-' }}</td>

                                    <td>
                                        <div class="btn-group" role="group">

                                            @can('ver turnos')
                                                <a href="{{ route('turno_horarios.show', $horario->id) }}"
                                                   class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('editar turnos')
                                                <a href="{{ route('turno_horarios.edit', $horario->id) }}"
                                                   class="btn btn-success btn-sm" title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar turnos')
                                                <form action="{{ route('turno_horarios.destroy', $horario->id) }}"
                                                      method="POST" style="display:inline-block;">
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
    .dataTables_wrapper{ width: 100%; }
    table.dataTable{ width: 100% !important; }
</style>
@stop

@section('js')
<script>
    $(function () {
        const dt = $('#turno_horarios').DataTable({
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

        setTimeout(() => {
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
            title: '¿Eliminar este horario?',
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

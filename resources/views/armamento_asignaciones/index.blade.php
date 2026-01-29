@extends('adminlte::page')

@section('title', 'Asignaciones de Armamento')

@section('content_header')
    <h1>Asignaciones de Armamento</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Historial de Asignaciones</h3>

                <div class="card-tools">
                    @can('crear armamento')
                        <a href="{{ route('armamento_asignaciones.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Nueva Asignación
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="weapon_assignments" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Arma</th>
                                <th>Matrícula</th>
                                <th>Tipo</th>
                                <th>Personal</th>
                                <th>Fecha asignación</th>
                                <th>Fecha devolución</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($weapon_assignments as $index => $wa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $wa->weapon->marca_modelo ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $wa->weapon->matricula ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $wa->weapon->tipo ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $wa->personal->nombres ?? '—' }}
                                    </td>

                                    <td>
                                        {{ optional($wa->fecha_asignacion)->format('d/m/Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($wa->fecha_devolucion)->format('d/m/Y') ?? '-' }}
                                    </td>

                                    <td>
                                        @if($wa->status === 'ASIGNADA')
                                            <span class="badge badge-success">ASIGNADA</span>
                                        @elseif($wa->status === 'DEVUELTA')
                                            <span class="badge badge-secondary">DEVUELTA</span>
                                        @elseif($wa->status === 'CANCELADA')
                                            <span class="badge badge-danger">CANCELADA</span>
                                        @else
                                            <span class="badge badge-dark">{{ $wa->status }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">

                                            @can('ver armamento')
                                                <a href="{{ route('armamento_asignaciones.show', $wa->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('editar armamento')
                                                <a href="{{ route('armamento_asignaciones.edit', $wa->id) }}"
                                                   class="btn btn-success btn-sm"
                                                   title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar armamento')
                                                <form action="{{ route('armamento_asignaciones.destroy', $wa->id) }}"
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
        const dt = $('#weapon_assignments').DataTable({
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
            title: '¿Eliminar asignación?',
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

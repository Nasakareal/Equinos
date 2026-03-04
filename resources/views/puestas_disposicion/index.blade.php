@extends('adminlte::page')

@section('title', 'Puestas a Disposición')

@section('content_header')
    <h1>Puestas a Disposición</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-danger">

            <div class="card-header">
                <h3 class="card-title">Listado de Puestas a Disposición</h3>

                <div class="card-tools d-flex align-items-center" style="gap:8px;">

                    <form method="GET" class="d-inline-block">
                        <input type="text"
                               name="buscar"
                               value="{{ request('buscar') }}"
                               class="form-control form-control-sm d-inline-block"
                               style="width:220px"
                               placeholder="Buscar folio u observaciones">
                    </form>

                    <form method="GET" class="d-inline-block">
                        <input type="number"
                               name="anio"
                               value="{{ request('anio') }}"
                               class="form-control form-control-sm d-inline-block"
                               style="width:120px"
                               placeholder="Año">
                    </form>

                    <form method="GET" class="d-inline-block">
                        <select name="personal_id" class="form-control form-control-sm d-inline-block" style="width:260px">
                            <option value="">Todo el personal</option>
                            @foreach($personals as $p)
                                <option value="{{ $p->id }}" {{ (string)request('personal_id')===(string)$p->id?'selected':'' }}>
                                    {{ trim(($p->grado ?? '').' '.($p->nombres ?? '')) }}{{ $p->cargo ? ' · '.$p->cargo : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <a href="{{ route('puestas_disposicion.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>

                    @can('crear puestas_disposicion')
                        <a href="{{ route('puestas_disposicion.create') }}" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-plus"></i> Registrar
                        </a>
                    @endcan

                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="puestas" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Folio</th>
                                <th>Año</th>
                                <th>Personal</th>
                                <th>PDF</th>
                                <th>Observaciones</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($puestas as $index => $pd)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <span class="badge badge-danger" style="font-size: 0.95rem;">
                                            {{ $pd->folio }}
                                        </span>
                                    </td>

                                    <td>{{ $pd->anio }}</td>

                                    <td>
                                        {{ trim(($pd->personal->grado ?? '').' '.($pd->personal->nombres ?? '')) ?: '-' }}
                                        @if(!empty($pd->personal->cargo))
                                            <div class="text-muted small">{{ $pd->personal->cargo }}</div>
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($pd->archivo_pdf))
                                            <a href="{{ Storage::disk('public')->url($pd->archivo_pdf) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="fa-solid fa-file-pdf"></i> Ver
                                            </a>
                                        @else
                                            <span class="text-muted">Sin archivo</span>
                                        @endif
                                    </td>

                                    <td style="text-align:left;">
                                        {{ $pd->observaciones ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $pd->created_at ? $pd->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('puestas_disposicion.show', $pd->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            @can('editar puestas_disposicion')
                                                <a href="{{ route('puestas_disposicion.edit', $pd->id) }}" class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('eliminar puestas_disposicion')
                                                <form action="{{ route('puestas_disposicion.destroy', $pd->id) }}" method="POST" style="display:inline-block;">
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

            @if(method_exists($puestas, 'links'))
                <div class="card-footer">
                    {{ $puestas->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

@stop


@section('css')
<style>

input.form-control,
textarea.form-control,
select.form-control {
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    background-color: #25364a !important;
    color: #ffffff !important;
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

::placeholder {
    color: #b8c7ce !important;
    opacity: 1;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

.btn-purple {
    background: linear-gradient(135deg, #6f42c1, #4e2a8e) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 8px 18px;
    box-shadow: 0 4px 10px rgba(111, 66, 193, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-purple:hover {
    background: linear-gradient(135deg, #5a32a3, #3d1f73) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(111, 66, 193, 0.45);
}

.btn-purple:focus,
.btn-purple:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.4) !important;
}

</style>
@stop


@section('js')

<script>
$(function () {

    $('#puestas').DataTable({
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

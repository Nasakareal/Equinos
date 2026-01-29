@extends('adminlte::page')

@section('title', 'Detalle del Tipo de Incidencia')

@section('content_header')
    <h1>Detalle del Tipo de Incidencia</h1>
@stop

@section('content')
<div class="row">

    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Información del Tipo de Incidencia</h3>

                <div class="card-tools">
                    @can('editar incidencias')
                        <a href="{{ route('incidence_types.edit', $incidence_type->id) }}"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Clave</th>
                            <td>{{ $incidence_type->clave }}</td>
                        </tr>

                        <tr>
                            <th>Nombre</th>
                            <td>{{ $incidence_type->nombre }}</td>
                        </tr>

                        <tr>
                            <th>¿Afecta servicio?</th>
                            <td>
                                @if((int)$incidence_type->afecta_servicio === 1)
                                    <span class="badge badge-warning">SÍ</span>
                                @else
                                    <span class="badge badge-secondary">NO</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Color</th>
                            <td>
                                @if(!empty($incidence_type->color))
                                    <span class="badge"
                                          style="background: {{ $incidence_type->color }}; color:#fff;">
                                        {{ $incidence_type->color }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Estatus</th>
                            <td>
                                @if((int)$incidence_type->activo === 1)
                                    <span class="badge badge-success">ACTIVO</span>
                                @else
                                    <span class="badge badge-secondary">INACTIVO</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de registro</th>
                            <td>{{ optional($incidence_type->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>

                        <tr>
                            <th>Última actualización</th>
                            <td>{{ optional($incidence_type->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="{{ route('incidence_types.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>

                @can('eliminar incidencias')
                    <form action="{{ route('incidence_types.destroy', $incidence_type->id) }}"
                          method="POST"
                          class="d-inline-block"
                          id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" id="delete-btn">
                            <i class="fa-regular fa-trash-can"></i> Eliminar
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
    .table th{
        background-color: rgba(255,255,255,.08);
        color: #ffffff;
        white-space: nowrap;
    }

    .table td{
        color: #ffffff;
        vertical-align: middle;
    }
</style>
@stop

@section('js')
<script>
    $(document).on('click', '#delete-btn', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Eliminar este tipo de incidencia?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
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
</script>
@stop

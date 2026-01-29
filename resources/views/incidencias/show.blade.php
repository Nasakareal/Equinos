@extends('adminlte::page')

@section('title', 'Detalle de Incidencia')

@section('content_header')
    <h1>Detalle de Incidencia</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Incidencia #{{ $incidence->id }}
                </h3>

                <div class="card-tools">
                    @can('editar incidencias')
                        <a href="{{ route('incidencias.edit', $incidence->id) }}"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                    <a href="{{ route('incidencias.index') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <!-- Personal -->
                    <div class="col-md-6">
                        <strong>Personal:</strong>
                        <p class="mb-2">
                            {{ $incidence->personal->nombres ?? '—' }}
                        </p>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-6">
                        <strong>Tipo de incidencia:</strong>
                        <p class="mb-2">
                            {{ $incidence->type->nombre ?? '—' }}
                        </p>
                    </div>

                </div>

                <div class="row">

                    <!-- Fecha inicio -->
                    <div class="col-md-4">
                        <strong>Fecha inicio:</strong>
                        <p class="mb-2">
                            {{ optional($incidence->fecha_inicio)->format('d/m/Y') }}
                        </p>
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-4">
                        <strong>Fecha fin:</strong>
                        <p class="mb-2">
                            @if(!empty($incidence->fecha_fin))
                                {{ optional($incidence->fecha_fin)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </p>
                    </div>

                    <!-- Registrado por -->
                    <div class="col-md-4">
                        <strong>Registrado por:</strong>
                        <p class="mb-2">
                            @if(!empty($incidence->registrado_por))
                                Usuario #{{ $incidence->registrado_por }}
                            @else
                                —
                            @endif
                        </p>
                    </div>

                </div>

                <hr>

                <!-- Comentario -->
                <div class="row">
                    <div class="col-md-12">
                        <strong>Comentario:</strong>
                        <div class="border rounded p-3 mt-2" style="background:#f8f9fa;">
                            {{ $incidence->comentario ?? 'Sin comentarios.' }}
                        </div>
                    </div>
                </div>

            </div>

            @can('eliminar incidencias')
            <div class="card-footer text-right">
                <form action="{{ route('incidencias.destroy', $incidence->id) }}"
                      method="POST"
                      style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger delete-btn">
                        <i class="fa-regular fa-trash-can"></i> Eliminar incidencia
                    </button>
                </form>
            </div>
            @endcan

        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Eliminar incidencia?',
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

    @if (session('success'))
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 10000
        });
    @endif
</script>
@stop

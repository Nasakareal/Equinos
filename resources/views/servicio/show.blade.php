{{-- resources/views/servicio/show.blade.php --}}

@extends('adminlte::page')

@section('title', 'Detalle del Horario de Servicio')

@section('content_header')
    <h1>Detalle del Horario de Servicio</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title">Información del Horario</h3>

                <div class="card-tools">
                    @can('editar turnos')
                        <a href="{{ route('servicio.edit', $service_schedule->id) }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                    <a href="{{ route('servicio.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- ID -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->id }}" readonly>
                        </div>
                    </div>

                    <!-- Turno -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Turno</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $service_schedule->turno->nombre ?? '-' }} {{ isset($service_schedule->turno->clave) ? '(' . $service_schedule->turno->clave . ')' : '' }}"
                                   readonly>
                        </div>
                    </div>

                    <!-- Tolerancia -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tolerancia (min)</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->min_tolerancia }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Hora entrada -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Hora de entrada</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->hora_entrada ?? '-' }}" readonly>
                        </div>
                    </div>

                    <!-- Hora salida -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Hora de salida</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->hora_salida ?? '-' }}" readonly>
                        </div>
                    </div>

                    <!-- Cruza día -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cruza día</label>
                            <div class="mt-2">
                                @if((int)$service_schedule->cruza_dia === 1)
                                    <span class="badge badge-warning p-2">Sí</span>
                                @else
                                    <span class="badge badge-secondary p-2">No</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Registro -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Registro</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->created_at ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Notas -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Notas</label>
                            <textarea class="form-control" rows="3" readonly>{{ $service_schedule->notas ?? '-' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Actualización -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Última actualización</label>
                            <input type="text" class="form-control" value="{{ $service_schedule->updated_at ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group" role="group">

                            @can('editar turnos')
                                <a href="{{ route('servicio.edit', $service_schedule->id) }}" class="btn btn-success">
                                    <i class="fa-regular fa-pen-to-square"></i> Editar
                                </a>
                            @endcan

                            @can('editar turnos')
                                <form action="{{ route('servicio.destroy', $service_schedule->id) }}"
                                      method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-btn">
                                        <i class="fa-regular fa-trash-can"></i> Eliminar
                                    </button>
                                </form>
                            @endcan

                            <a href="{{ route('servicio.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Volver al listado
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .form-group label { font-weight: bold; }
</style>
@stop

@section('js')
<script>
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
            title: '¿Eliminar este horario de servicio?',
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

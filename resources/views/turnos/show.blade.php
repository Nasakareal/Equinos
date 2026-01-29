{{-- resources/views/turnos/show.blade.php --}}

@extends('adminlte::page')

@section('title', 'Detalle del Turno')

@section('content_header')
    <h1>Detalle del Turno</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Información del Turno</h3>

                    <div class="card-tools">
                        @can('editar turnos')
                            <a href="{{ route('turnos.edit', $turno->id) }}" class="btn btn-success btn-sm">
                                <i class="fa-regular fa-pen-to-square"></i> Editar
                            </a>
                        @endcan

                        <a href="{{ route('turnos.index') }}" class="btn btn-secondary btn-sm">
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
                                <input type="text" class="form-control" value="{{ $turno->id }}" readonly>
                            </div>
                        </div>

                        <!-- Clave -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Clave</label>
                                <input type="text" class="form-control" value="{{ $turno->clave ?? '-' }}" readonly>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="mt-2">
                                    @if(!empty($turno->activo) && (int)$turno->activo === 1)
                                        <span class="badge badge-success p-2">ACTIVO</span>
                                    @else
                                        <span class="badge badge-secondary p-2">INACTIVO</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Registro</label>
                                <input type="text" class="form-control" value="{{ $turno->created_at ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" value="{{ $turno->nombre ?? '-' }}" readonly>
                            </div>
                        </div>

                        <!-- Actualización -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Última actualización</label>
                                <input type="text" class="form-control" value="{{ $turno->updated_at ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Descripción -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" rows="3" readonly>{{ $turno->descripcion ?? '-' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                @can('editar turnos')
                                    <a href="{{ route('turnos.edit', $turno->id) }}" class="btn btn-success">
                                        <i class="fa-regular fa-pen-to-square"></i> Editar
                                    </a>
                                @endcan

                                @can('eliminar turnos')
                                    <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger delete-btn">
                                            <i class="fa-regular fa-trash-can"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan

                                <a href="{{ route('turnos.index') }}" class="btn btn-secondary">
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

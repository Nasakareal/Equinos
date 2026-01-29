{{-- resources/views/turnos/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Editar Turno')

@section('content_header')
    <h1>Editar Turno</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modificar Datos del Turno</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('turnos.update', $turno->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Clave -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="clave">Clave</label>
                                    <input type="text"
                                           name="clave"
                                           id="clave"
                                           class="form-control @error('clave') is-invalid @enderror"
                                           value="{{ old('clave', $turno->clave) }}"
                                           required>
                                    @error('clave')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text"
                                           name="nombre"
                                           id="nombre"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $turno->nombre) }}"
                                           required>
                                    @error('nombre')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Activo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="activo">Estatus</label>

                                    {{-- hidden para que siempre se envíe --}}
                                    <input type="hidden" name="activo" value="0">

                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="activo"
                                               name="activo"
                                               value="1"
                                               {{ old('activo', $turno->activo) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="activo">Activo</label>
                                    </div>

                                    @error('activo')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Descripción -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion"
                                              id="descripcion"
                                              rows="3"
                                              class="form-control @error('descripcion') is-invalid @enderror"
                                              placeholder="Descripción del turno...">{{ old('descripcion', $turno->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-save"></i> Guardar Cambios
                                    </button>
                                    <a href="{{ route('turnos.index') }}" class="btn btn-secondary">
                                        <i class="fa-solid fa-ban"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>
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
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: `
                    <ul style="text-align:left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Aceptar'
            });
        @endif
    </script>
@stop

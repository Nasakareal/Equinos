@extends('adminlte::page')

@section('title', 'Nuevo Tipo de Incidencia')

@section('content_header')
    <h1>Nuevo Tipo de Incidencia</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Llene los Datos</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('incidence_types.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Clave -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave">Clave</label>
                                <input type="text"
                                       name="clave"
                                       id="clave"
                                       class="form-control @error('clave') is-invalid @enderror"
                                       value="{{ old('clave') }}"
                                       placeholder="Ej. FRANCO, VACACIONES"
                                       required>
                                @error('clave')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text"
                                       name="nombre"
                                       id="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej. Franco, Vacaciones, Licencia laboral"
                                       required>
                                @error('nombre')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Afecta servicio -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="afecta_servicio">¿Afecta servicio?</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="afecta_servicio"
                                           name="afecta_servicio"
                                           value="1"
                                           {{ old('afecta_servicio', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="afecta_servicio">
                                        Sí
                                    </label>
                                </div>
                                @error('afecta_servicio')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color">Color (opcional)</label>
                                <input type="text"
                                       name="color"
                                       id="color"
                                       class="form-control @error('color') is-invalid @enderror"
                                       value="{{ old('color') }}"
                                       placeholder="Ej. #28a745 o red">
                                @error('color')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Activo -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="activo">Estatus</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="activo"
                                           name="activo"
                                           value="1"
                                           {{ old('activo', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="activo">
                                        Activo
                                    </label>
                                </div>
                                @error('activo')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Guardar
                    </button>

                    <a href="{{ route('incidence_types.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-ban"></i> Cancelar
                    </a>

                </form>
            </div>
        </div>
    </div>
</div>
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

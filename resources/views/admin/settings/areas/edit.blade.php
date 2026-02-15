@extends('adminlte::page')

@section('title', 'Editar Área')

@section('content_header')
    <h1>Editar Área</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Modificar Área</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('areas.update', $area->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- CLAVE --}}
                        <div class="form-group">
                            <label for="clave">Clave del Área</label>
                            <input type="text"
                                   name="clave"
                                   id="clave"
                                   class="form-control @error('clave') is-invalid @enderror"
                                   value="{{ old('clave', $area->clave) }}"
                                   required>
                            @error('clave')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- NOMBRE --}}
                        <div class="form-group">
                            <label for="nombre">Nombre del Área</label>
                            <input type="text"
                                   name="nombre"
                                   id="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $area->nombre) }}"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- ACTIVO --}}
                        <div class="form-group">
                            <label for="activo">Estatus</label>
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="activo"
                                       name="activo"
                                       value="1"
                                       {{ old('activo', $area->activo) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">Área Activa</label>
                            </div>
                        </div>

                        <hr>

                        {{-- BOTONES --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-save"></i> Guardar Cambios
                            </button>

                            <a href="{{ route('areas.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-ban"></i> Cancelar
                            </a>
                        </div>

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

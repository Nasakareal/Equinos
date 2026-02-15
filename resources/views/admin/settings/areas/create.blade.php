@extends('adminlte::page')

@section('title', 'Crear Área')

@section('content_header')
    <h1>Crear Nueva Área</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Registro de Área</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('areas.store') }}" method="POST">
                        @csrf

                        {{-- CLAVE --}}
                        <div class="form-group">
                            <label for="clave">Clave del Área</label>
                            <input type="text"
                                   name="clave"
                                   id="clave"
                                   class="form-control @error('clave') is-invalid @enderror"
                                   value="{{ old('clave') }}"
                                   placeholder="Ej. CANINA, OPERATIVA..."
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
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej. ÁREA CANINA"
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
                                       {{ old('activo', 1) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">Área Activa</label>
                            </div>
                        </div>

                        <hr>

                        {{-- BOTONES --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i> Guardar Área
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

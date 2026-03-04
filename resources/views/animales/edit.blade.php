@extends('adminlte::page')

@section('title', 'Editar Animal')

@section('content_header')
    <h1>Editar Animal: {{ $animal->nombre }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-purple">
            <div class="card-header">
                <h3 class="card-title">Actualizar registro</h3>

                <div class="card-tools">
                    <a href="{{ url('/animales/'.$animal->id) }}" class="btn btn-info btn-sm">
                        <i class="fa-regular fa-eye"></i> Ver
                    </a>

                    <a href="{{ url('/animales') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('animales.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- FOTO --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-purple">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa-solid fa-camera"></i> Foto
                                    </h3>
                                </div>

                                <div class="card-body">

                                    @if(!empty($animal->foto_url))
                                        <div class="mb-3 text-center">
                                            <a href="{{ $animal->foto_url }}" target="_blank" rel="noopener">
                                                <img
                                                    src="{{ $animal->foto_url }}"
                                                    alt="Foto de {{ $animal->nombre }}"
                                                    class="img-fluid rounded shadow"
                                                    style="max-height: 280px; object-fit: cover;"
                                                >
                                            </a>
                                            <div class="mt-2 text-muted small">Foto actual (click para abrir)</div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fa-regular fa-image fa-2x mb-2"></i>
                                            <div>Sin foto</div>
                                        </div>
                                    @endif

                                    <div class="form-group mb-0">
                                        <label>Cambiar foto</label>
                                        <input type="file"
                                               name="foto"
                                               accept="image/*"
                                               class="form-control @error('foto') is-invalid @enderror">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-1">
                                            Formatos: JPG, JPEG, PNG, WEBP · Máx 4MB
                                        </small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control @error('tipo') is-invalid @enderror">
                                    <option value="EQUINO" {{ old('tipo', $animal->tipo)=='EQUINO'?'selected':'' }}>EQUINO</option>
                                    <option value="CANINO" {{ old('tipo', $animal->tipo)=='CANINO'?'selected':'' }}>CANINO</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text"
                                       name="nombre"
                                       value="{{ old('nombre', $animal->nombre) }}"
                                       class="form-control @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Raza</label>
                                <input type="text"
                                       name="raza"
                                       value="{{ old('raza', $animal->raza) }}"
                                       class="form-control @error('raza') is-invalid @enderror">
                                @error('raza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Procedencia</label>
                                <input type="text"
                                       name="procedencia"
                                       value="{{ old('procedencia', $animal->procedencia) }}"
                                       class="form-control @error('procedencia') is-invalid @enderror">
                                @error('procedencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estatus</label>
                                <select name="estatus" class="form-control @error('estatus') is-invalid @enderror">
                                    <option value="ACTIVO" {{ old('estatus', $animal->estatus)=='ACTIVO'?'selected':'' }}>ACTIVO</option>
                                    <option value="BAJA" {{ old('estatus', $animal->estatus)=='BAJA'?'selected':'' }}>BAJA</option>
                                    <option value="RESGUARDO" {{ old('estatus', $animal->estatus)=='RESGUARDO'?'selected':'' }}>RESGUARDO</option>
                                </select>
                                @error('estatus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="sexo" class="form-control @error('sexo') is-invalid @enderror">
                                    <option value="">-- Selecciona --</option>
                                    <option value="MACHO" {{ old('sexo', $animal->sexo)=='MACHO'?'selected':'' }}>MACHO</option>
                                    <option value="HEMBRA" {{ old('sexo', $animal->sexo)=='HEMBRA'?'selected':'' }}>HEMBRA</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text"
                                       name="color"
                                       value="{{ old('color', $animal->color) }}"
                                       class="form-control @error('color') is-invalid @enderror">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Especialidad</label>
                                <input type="text"
                                       name="especialidad"
                                       value="{{ old('especialidad', $animal->especialidad) }}"
                                       class="form-control @error('especialidad') is-invalid @enderror">
                                @error('especialidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Marcaje</label>
                                <input type="text"
                                       name="marcaje"
                                       value="{{ old('marcaje', $animal->marcaje) }}"
                                       class="form-control @error('marcaje') is-invalid @enderror">
                                @error('marcaje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chip</label>
                                <input type="text"
                                       name="chip"
                                       value="{{ old('chip', $animal->chip) }}"
                                       class="form-control @error('chip') is-invalid @enderror">
                                @error('chip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha nacimiento</label>
                                <input type="date"
                                       name="fecha_nacimiento"
                                       value="{{ old('fecha_nacimiento', optional($animal->fecha_nacimiento)->format('Y-m-d')) }}"
                                       class="form-control @error('fecha_nacimiento') is-invalid @enderror">
                                @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Edad (texto)</label>
                                <input type="text"
                                       name="edad_texto"
                                       value="{{ old('edad_texto', $animal->edad_texto) }}"
                                       class="form-control @error('edad_texto') is-invalid @enderror"
                                       placeholder="Ej: 05 AÑOS 11 MESES">
                                @error('edad_texto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Forraje (kg/día)</label>
                                <input type="number"
                                       step="0.01"
                                       name="forraje_kg_diario"
                                       value="{{ old('forraje_kg_diario', $animal->forraje_kg_diario) }}"
                                       class="form-control @error('forraje_kg_diario') is-invalid @enderror">
                                @error('forraje_kg_diario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Grano (kg/día)</label>
                                <input type="number"
                                       step="0.01"
                                       name="grano_kg_diario"
                                       value="{{ old('grano_kg_diario', $animal->grano_kg_diario) }}"
                                       class="form-control @error('grano_kg_diario') is-invalid @enderror">
                                @error('grano_kg_diario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Características</label>
                                <input type="text"
                                       name="caracteristicas"
                                       value="{{ old('caracteristicas', $animal->caracteristicas) }}"
                                       class="form-control @error('caracteristicas') is-invalid @enderror">
                                @error('caracteristicas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones"
                                          rows="3"
                                          class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $animal->observaciones) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-purple">
                        <i class="fa-solid fa-check"></i> Actualizar
                    </button>
                </div>

            </form>

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
@if ($errors->any())
Swal.fire({
    icon: 'error',
    title: 'Revisa el formulario',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
@endif
</script>
@stop

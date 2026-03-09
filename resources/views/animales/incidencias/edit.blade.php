@extends('adminlte::page')

@section('title', 'Editar Incidencia')

@section('content_header')
    <h1>Editar Incidencia · {{ $animal->nombre }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Editar incidencia</h3>

                <div class="card-tools">
                    <a href="{{ url('/animales/'.$animal->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('animales.incidencias.update', [$animal->id, $incidence->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        {{-- FECHA --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="datetime-local"
                                       name="fecha"
                                       value="{{ old('fecha', \Carbon\Carbon::parse($incidence->fecha)->format('Y-m-d\TH:i')) }}"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       required>

                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TIPO --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo de incidencia</label>

                                <select name="incidence_type_id"
                                        class="form-control @error('incidence_type_id') is-invalid @enderror">

                                    <option value="">-- Selecciona --</option>

                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('incidence_type_id', $incidence->incidence_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->nombre }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('incidence_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted d-block mt-1">
                                    Solo tipos de incidencia para animales.
                                </small>

                            </div>
                        </div>

                        {{-- GRAVEDAD --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gravedad</label>

                                <select name="gravedad"
                                        class="form-control @error('gravedad') is-invalid @enderror"
                                        required>

                                    <option value="">-- Selecciona --</option>

                                    <option value="BAJA"
                                        {{ old('gravedad', $incidence->gravedad) == 'BAJA' ? 'selected' : '' }}>
                                        BAJA
                                    </option>

                                    <option value="MEDIA"
                                        {{ old('gravedad', $incidence->gravedad) == 'MEDIA' ? 'selected' : '' }}>
                                        MEDIA
                                    </option>

                                    <option value="ALTA"
                                        {{ old('gravedad', $incidence->gravedad) == 'ALTA' ? 'selected' : '' }}>
                                        ALTA
                                    </option>

                                </select>

                                @error('gravedad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>Descripción</label>

                                <textarea name="descripcion"
                                          rows="4"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Describe la incidencia">{{ old('descripcion', $incidence->descripcion) }}</textarea>

                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">

                    <button type="submit" class="btn btn-warning">
                        <i class="fa-solid fa-save"></i> Actualizar
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
    border-color: #f39c12 !important;
    box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25) !important;
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

.btn-warning {
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(243, 156, 18, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(243, 156, 18, 0.45);
}

.btn-warning:focus,
.btn-warning:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.35) !important;
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

@if(session('success'))
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 2500
});
@endif

</script>
@stop

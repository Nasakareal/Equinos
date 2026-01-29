@extends('adminlte::page')

@section('title', 'Nueva Incidencia')

@section('content_header')
    <h1>Nueva Incidencia</h1>
@stop

@section('content')
@php
    // ✅ Prioridad: old() si hay errores; si no, el que viene por query (?personal_id=)
    $personalSeleccionado = old('personal_id', $personal_id_preseleccionado ?? '');
    $bloquearPersonal = !empty($personal_id_preseleccionado) && empty(old('personal_id'));
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Llene los Datos</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('incidencias.store') }}" method="POST">
                    @csrf

                    {{-- ✅ Si el select va bloqueado, enviamos el valor por hidden (disabled NO se envía) --}}
                    @if($bloquearPersonal)
                        <input type="hidden" name="personal_id" value="{{ $personalSeleccionado }}">
                    @endif

                    <div class="row">
                        <!-- Personal -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="personal_id">Personal</label>
                                <select name="personal_id"
                                        id="personal_id"
                                        class="form-control @error('personal_id') is-invalid @enderror"
                                        required
                                        {{ $bloquearPersonal ? 'disabled' : '' }}>
                                    <option value="" disabled {{ empty($personalSeleccionado) ? 'selected' : '' }}>
                                        Seleccione...
                                    </option>

                                    @foreach ($personals as $p)
                                        <option value="{{ $p->id }}"
                                            {{ (string)$personalSeleccionado === (string)$p->id ? 'selected' : '' }}>
                                            {{ $p->nombres }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('personal_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if($bloquearPersonal)
                                    <small class="text-muted">
                                        Personal fijado porque llegaste desde el detalle del elemento.
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Tipo de incidencia -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="incidence_type_id">Tipo de incidencia</label>
                                <select name="incidence_type_id"
                                        id="incidence_type_id"
                                        class="form-control @error('incidence_type_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($incidence_types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('incidence_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('incidence_type_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Fecha inicio -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <input type="date"
                                       name="fecha_inicio"
                                       id="fecha_inicio"
                                       class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_inicio')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Fecha fin -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin (opcional)</label>
                                <input type="date"
                                       name="fecha_fin"
                                       id="fecha_fin"
                                       class="form-control @error('fecha_fin') is-invalid @enderror"
                                       value="{{ old('fecha_fin') }}">
                                @error('fecha_fin')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Si aplica rango (ej. vacaciones/licencia). Si es un solo día, déjalo vacío.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Comentario -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="comentario">Comentario (opcional)</label>
                                <textarea name="comentario"
                                          id="comentario"
                                          rows="4"
                                          class="form-control @error('comentario') is-invalid @enderror"
                                          placeholder="Detalles de la incidencia...">{{ old('comentario') }}</textarea>
                                @error('comentario')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Registrar incidencia
                    </button>

                    <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">
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

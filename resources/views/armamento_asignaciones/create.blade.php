@extends('adminlte::page')

@section('title', 'Nueva Asignación de Armamento')

@section('content_header')
    <h1>Nueva Asignación de Armamento</h1>
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
                <form action="{{ route('armamento_asignaciones.store') }}" method="POST">
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

                        <!-- Arma -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weapon_id">Arma</label>
                                <select name="weapon_id"
                                        id="weapon_id"
                                        class="form-control @error('weapon_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($weapons as $w)
                                        <option value="{{ $w->id }}"
                                            {{ old('weapon_id') == $w->id ? 'selected' : '' }}>
                                            {{ $w->tipo }} — {{ $w->matricula }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('weapon_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">
                                    Solo puede haber una asignación activa por arma.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Fecha asignación -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_asignacion">Fecha de asignación</label>
                                <input type="date"
                                       name="fecha_asignacion"
                                       id="fecha_asignacion"
                                       class="form-control @error('fecha_asignacion') is-invalid @enderror"
                                       value="{{ old('fecha_asignacion') }}">
                                @error('fecha_asignacion')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Fecha devolución -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_devolucion">Fecha de devolución</label>
                                <input type="date"
                                       name="fecha_devolucion"
                                       id="fecha_devolucion"
                                       class="form-control @error('fecha_devolucion') is-invalid @enderror"
                                       value="{{ old('fecha_devolucion') }}">
                                @error('fecha_devolucion')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Estatus -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Estatus</label>
                                <select name="status"
                                        id="status"
                                        class="form-control @error('status') is-invalid @enderror"
                                        required>
                                    <option value="ASIGNADA" {{ old('status', 'ASIGNADA') === 'ASIGNADA' ? 'selected' : '' }}>
                                        ASIGNADA
                                    </option>
                                    <option value="DEVUELTA" {{ old('status') === 'DEVUELTA' ? 'selected' : '' }}>
                                        DEVUELTA
                                    </option>
                                    <option value="CANCELADA" {{ old('status') === 'CANCELADA' ? 'selected' : '' }}>
                                        CANCELADA
                                    </option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Observaciones -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="3"
                                          class="form-control @error('observaciones') is-invalid @enderror"
                                          placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Registrar asignación
                    </button>

                    <a href="{{ route('armamento_asignaciones.index') }}" class="btn btn-secondary">
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

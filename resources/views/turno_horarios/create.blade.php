{{-- resources/views/turno_horarios/create.blade.php --}}

@extends('adminlte::page')

@section('title', 'Crear Horario de Turno')

@section('content_header')
    <h1>Crear Horario de Turno</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Llene los Datos</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('turno_horarios.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Turno -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="turno_id">Turno</label>
                                <select name="turno_id"
                                        id="turno_id"
                                        class="form-control @error('turno_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($turnos as $turno)
                                        <option value="{{ $turno->id }}" {{ old('turno_id') == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->nombre }} ({{ $turno->clave }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('turno_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Hora entrada -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_entrada">Hora de entrada</label>
                                <input type="time"
                                       name="hora_entrada"
                                       id="hora_entrada"
                                       class="form-control @error('hora_entrada') is-invalid @enderror"
                                       value="{{ old('hora_entrada') }}">
                                @error('hora_entrada')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Opcional.</small>
                            </div>
                        </div>

                        <!-- Hora salida -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_salida">Hora de salida</label>
                                <input type="time"
                                       name="hora_salida"
                                       id="hora_salida"
                                       class="form-control @error('hora_salida') is-invalid @enderror"
                                       value="{{ old('hora_salida') }}">
                                @error('hora_salida')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Opcional.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tolerancia -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_tolerancia">Minutos de tolerancia</label>
                                <input type="number"
                                       name="min_tolerancia"
                                       id="min_tolerancia"
                                       class="form-control @error('min_tolerancia') is-invalid @enderror"
                                       value="{{ old('min_tolerancia', 0) }}"
                                       min="0"
                                       max="1440"
                                       required>
                                @error('min_tolerancia')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">0 = sin tolerancia.</small>
                            </div>
                        </div>

                        <!-- Cruza día -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cruza_dia">Cruza día</label>

                                {{-- hidden para que siempre se envíe 0 --}}
                                <input type="hidden" name="cruza_dia" value="0">

                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="cruza_dia"
                                           name="cruza_dia"
                                           value="1"
                                           {{ old('cruza_dia') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="cruza_dia">Sí</label>
                                </div>

                                @error('cruza_dia')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Márcalo si la salida corresponde al día siguiente.</small>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="notas">Notas</label>
                                <input type="text"
                                       name="notas"
                                       id="notas"
                                       class="form-control @error('notas') is-invalid @enderror"
                                       value="{{ old('notas') }}"
                                       placeholder="Opcional (máx. 255)">
                                @error('notas')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check"></i> Registrar
                                </button>
                                <a href="{{ route('turno_horarios.index') }}" class="btn btn-secondary">
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

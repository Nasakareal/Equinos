@extends('adminlte::page')

@section('title', 'Agregar Registro Médico')

@section('content_header')
    <h1>Agregar Registro Médico - {{ $animal->nombre }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">Datos del Registro Médico</h3>
            </div>

            <div class="card-body">

                <div class="alert alert-info">
                    Primero guarda el registro médico. Después podrás subir archivos desde <b>Editar</b>.
                </div>

                <form action="{{ route('animales.medico.store', $animal->id) }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha *</label>
                                <input type="date"
                                       name="fecha"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       value="{{ old('fecha') }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <input type="text"
                                       name="tipo"
                                       class="form-control @error('tipo') is-invalid @enderror"
                                       placeholder="Vacunación, Cirugía, Desparasitación..."
                                       value="{{ old('tipo') }}"
                                       required>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Veterinario</label>
                                <input type="text"
                                       name="veterinario"
                                       class="form-control @error('veterinario') is-invalid @enderror"
                                       value="{{ old('veterinario') }}">
                                @error('veterinario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Costo</label>
                                <input type="number"
                                       step="0.01"
                                       name="costo"
                                       class="form-control @error('costo') is-invalid @enderror"
                                       value="{{ old('costo') }}">
                                @error('costo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Próxima cita</label>
                                <input type="date"
                                       name="proxima_cita"
                                       class="form-control @error('proxima_cita') is-invalid @enderror"
                                       value="{{ old('proxima_cita') }}">
                                @error('proxima_cita')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion"
                                          rows="3"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Detalles del tratamiento...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('animales.show', $animal->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al expediente
                        </a>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Registro
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@stop

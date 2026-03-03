@extends('adminlte::page')

@section('title', 'Editar Registro Médico')

@section('content_header')
    <h1>Editar Registro Médico - {{ $animal->nombre }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Editar Datos del Registro Médico</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('animales.medico.update', [$animal->id, $record->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha *</label>
                                <input type="date"
                                       name="fecha"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       value="{{ old('fecha', optional($record->fecha)->format('Y-m-d')) }}"
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
                                       value="{{ old('tipo', $record->tipo) }}"
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
                                       value="{{ old('veterinario', $record->veterinario) }}">
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
                                       value="{{ old('costo', $record->costo) }}">
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
                                       value="{{ old('proxima_cita', optional($record->proxima_cita)->format('Y-m-d')) }}">
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
                                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $record->descripcion) }}</textarea>
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

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar Registro
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="card card-outline card-primary mt-3">
            <div class="card-header">
                <h3 class="card-title">Archivos del Registro</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('animales.medico.files.store', [$animal->id, $record->id]) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="mb-3">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <input type="file"
                                   name="archivo"
                                   required
                                   class="form-control @error('archivo') is-invalid @enderror">
                            @error('archivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-info btn-block">
                                <i class="fas fa-upload"></i> Subir archivo
                            </button>
                        </div>
                    </div>
                </form>

                @if(($record->files ?? collect())->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Archivo</th>
                                    <th>Tipo</th>
                                    <th width="120">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($record->files as $file)
                                    <tr>
                                        <td>
                                            <a href="{{ Storage::url($file->archivo) }}" target="_blank">
                                                <i class="fas fa-file"></i> Ver / Descargar
                                            </a>
                                        </td>
                                        <td>{{ $file->tipo }}</td>
                                        <td>
                                            <form action="{{ route('animales.medico.files.destroy', $file->id) }}"
                                                  method="POST"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Eliminar archivo?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Este registro aún no tiene archivos.
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@stop

{{-- resources/views/personal/documentos/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Editar Documento')

@section('content_header')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Editar Documento</h1>

    <a href="{{ route('personal.documentos.index', $personal->id) }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>
@stop


@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-warning">

            <div class="card-header">
                <h3 class="card-title">
                    Documento de {{ $personal->nombres }}
                </h3>
            </div>

            <form action="{{ route('personal.documentos.update', [$personal->id, $documento->id]) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Título *</label>
                                <input type="text"
                                       name="titulo"
                                       class="form-control"
                                       required
                                       value="{{ old('titulo', $documento->titulo) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <input type="text"
                                       name="tipo_documento"
                                       class="form-control"
                                       value="{{ old('tipo_documento', $documento->tipo_documento) }}">
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha del documento</label>
                                <input type="date"
                                       name="fecha_documento"
                                       class="form-control"
                                       value="{{ old('fecha_documento', $documento->fecha_documento) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reemplazar archivo</label>

                                <input type="file"
                                       name="archivo"
                                       class="form-control">

                                <small class="text-muted">
                                    Si subes un archivo nuevo, el anterior será reemplazado.
                                </small>

                                @if($documento->nombre_original)
                                    <div class="mt-2">
                                        <strong>Archivo actual:</strong>
                                        {{ $documento->nombre_original }}

                                        <a href="{{ route('personal.documentos.download', [$personal->id, $documento->id]) }}"
                                           class="btn btn-info btn-xs ml-2">
                                            <i class="fa-solid fa-download"></i>
                                            Descargar
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion"
                                          class="form-control"
                                          rows="3">{{ old('descripcion', $documento->descripcion) }}</textarea>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones"
                                          class="form-control"
                                          rows="3">{{ old('observaciones', $documento->observaciones) }}</textarea>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estatus</label>

                                <select name="activo" class="form-control">
                                    <option value="1" {{ $documento->activo ? 'selected' : '' }}>
                                        Activo
                                    </option>

                                    <option value="0" {{ !$documento->activo ? 'selected' : '' }}>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="card-footer d-flex justify-content-between">

                    <a href="{{ route('personal.documentos.index', $personal->id) }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save"></i> Actualizar documento
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
@stop


@section('css')
<style>

.form-control{
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

.form-control:focus{
    background-color: #25364a !important;
    color: #ffffff !important;
}

</style>
@stop


@section('js')

@if ($errors->any())
<script>

Swal.fire({
    icon: 'error',
    title: 'Error',
    html: `{!! implode('<br>', $errors->all()) !!}`
});

</script>
@endif

@stop

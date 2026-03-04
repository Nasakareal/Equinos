@extends('adminlte::page')

@section('title', 'Editar Puesta a Disposición')

@section('content_header')
    <h1>Editar Puesta a Disposición</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">
                    Folio: {{ $pd->folio }}
                </h3>

                <div class="card-tools">
                    <a href="{{ route('puestas_disposicion.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    <a href="{{ route('puestas_disposicion.show', $pd) }}" class="btn btn-info btn-sm">
                        <i class="fa-regular fa-eye"></i> Ver
                    </a>
                </div>
            </div>

            <form action="{{ route('puestas_disposicion.update', $pd) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Personal</label>
                                <select name="personal_id" class="form-control @error('personal_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar</option>

                                    @foreach($personals as $personal)
                                        <option value="{{ $personal->id }}"
                                            {{ old('personal_id', $pd->personal_id) == $personal->id ? 'selected' : '' }}>
                                            {{ $personal->grado }} {{ $personal->nombres }} ({{ $personal->cargo }})
                                        </option>
                                    @endforeach

                                </select>
                                @error('personal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Año</label>
                                <input type="number"
                                       class="form-control"
                                       value="{{ $pd->anio }}"
                                       disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Folio</label>
                                <input type="text"
                                       name="folio"
                                       class="form-control @error('folio') is-invalid @enderror"
                                       value="{{ old('folio', $pd->folio) }}"
                                       required>
                                @error('folio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Se captura manualmente y puede incluir letras/símbolos.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Reemplazar PDF</label>
                                <input type="file"
                                       name="archivo_pdf"
                                       class="form-control @error('archivo_pdf') is-invalid @enderror"
                                       accept="application/pdf">
                                @error('archivo_pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Si no seleccionas archivo, se conserva el actual.</small>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones"
                                          class="form-control @error('observaciones') is-invalid @enderror"
                                          rows="3">{{ old('observaciones', $pd->observaciones) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    @if($pd->archivo_pdf)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Archivo actual:</strong>
                                    <a href="{{ asset('storage/'.$pd->archivo_pdf) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-info ml-2">
                                        <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="card-footer">

                    <button type="submit" class="btn btn-purple">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar
                    </button>

                    <a href="{{ route('puestas_disposicion.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>

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
    title: 'Error',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
@endif

@if(session('success'))
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 3000
});
@endif
</script>
@stop

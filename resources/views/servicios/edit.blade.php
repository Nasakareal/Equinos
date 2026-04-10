@extends('adminlte::page')

@section('title', 'Editar Servicio')

@section('content_header')
    <h1>Editar Servicio / Apoyo / Memorándum</h1>
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('/servicios/' . $servicio->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Datos del servicio</h3>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de registro</label>
                        <select name="categoria_registro" id="categoria_registro" class="form-control @error('categoria_registro') is-invalid @enderror" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO" {{ old('categoria_registro', $servicio->categoria_registro) == 'SERVICIO' ? 'selected' : '' }}>SERVICIO</option>
                            <option value="APOYO" {{ old('categoria_registro', $servicio->categoria_registro) == 'APOYO' ? 'selected' : '' }}>APOYO</option>
                            <option value="MEMORANDUM" {{ old('categoria_registro', $servicio->categoria_registro) == 'MEMORANDUM' ? 'selected' : '' }}>MEMORANDUM</option>
                        </select>
                        @error('categoria_registro')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control @error('tipo_servicio') is-invalid @enderror" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'SEGURIDAD' ? 'selected' : '' }}>SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'BARRIDOS DE SEGURIDAD' ? 'selected' : '' }}>BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'BUSQUEDA' ? 'selected' : '' }}>BUSQUEDA</option>
                            <option value="DESFILES" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'DESFILES' ? 'selected' : '' }}>DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'PROXIMIDAD SOCIAL' ? 'selected' : '' }}>PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'ACTOS CIVICOS' ? 'selected' : '' }}>ACTOS CIVICOS</option>
                            <option value="OTRO" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>
                        @error('tipo_servicio')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número / Referencia</label>
                        <input type="text" name="folio_referencia" class="form-control @error('folio_referencia') is-invalid @enderror" value="{{ old('folio_referencia', $servicio->folio_referencia) }}">
                        @error('folio_referencia')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('Y-m-d') : '') }}" required>
                        @error('fecha')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time" name="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora', $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '') }}" required>
                        @error('hora')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Municipio</label>
                        <input type="text" name="municipio" class="form-control @error('municipio') is-invalid @enderror" value="{{ old('municipio', $servicio->municipio) }}">
                        @error('municipio')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lugar</label>
                        <input type="text" name="lugar" class="form-control @error('lugar') is-invalid @enderror" value="{{ old('lugar', $servicio->lugar) }}">
                        @error('lugar')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Asunto base</label>
                        <input type="text" name="asunto" id="asunto" class="form-control @error('asunto') is-invalid @enderror" value="{{ old('asunto', $servicio->asunto) }}">
                        @error('asunto')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 {{ old('tipo_servicio', $servicio->tipo_servicio) == 'BUSQUEDA' ? '' : 'd-none' }}" id="bloque_busqueda">
                    <div class="form-group">
                        <label>Tipo de búsqueda</label>
                        <select name="tipo_busqueda" class="form-control @error('tipo_busqueda') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            <option value="EN VIDA" {{ old('tipo_busqueda', $servicio->tipo_busqueda) == 'EN VIDA' ? 'selected' : '' }}>EN VIDA</option>
                            <option value="RECURSO HUMANO" {{ old('tipo_busqueda', $servicio->tipo_busqueda) == 'RECURSO HUMANO' ? 'selected' : '' }}>RECURSO HUMANO</option>
                            <option value="EXPLOSIVO" {{ old('tipo_busqueda', $servicio->tipo_busqueda) == 'EXPLOSIVO' ? 'selected' : '' }}>EXPLOSIVO</option>
                            <option value="FORENSE" {{ old('tipo_busqueda', $servicio->tipo_busqueda) == 'FORENSE' ? 'selected' : '' }}>FORENSE</option>
                            <option value="NARCOTICOS" {{ old('tipo_busqueda', $servicio->tipo_busqueda) == 'NARCOTICOS' ? 'selected' : '' }}>NARCOTICOS</option>
                        </select>
                        @error('tipo_busqueda')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Elemento</label>
                        <select name="personal_id" class="form-control @error('personal_id') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach($personales as $p)
                                <option value="{{ $p->id }}" {{ old('personal_id', $servicio->personal_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombres }}
                                </option>
                            @endforeach
                        </select>
                        @error('personal_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Canino</label>
                        <select name="canino_id" class="form-control @error('canino_id') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach($caninos as $c)
                                <option value="{{ $c->id }}" {{ old('canino_id', $servicio->canino_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('canino_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Equino</label>
                        <select name="equino_id" class="form-control @error('equino_id') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach($equinos as $e)
                                <option value="{{ $e->id }}" {{ old('equino_id', $servicio->equino_id) == $e->id ? 'selected' : '' }}>
                                    {{ $e->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('equino_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Patrulla</label>
                        <select name="patrulla_id" class="form-control @error('patrulla_id') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach($patrullas as $p)
                                <option value="{{ $p->id }}" {{ old('patrulla_id', $servicio->patrulla_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre ?? $p->numero ?? $p->placas ?? 'ID '.$p->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('patrulla_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>¿Se cumplió?</label>
                        <select name="cumplio" class="form-control @error('cumplio') is-invalid @enderror">
                            <option value="1" {{ old('cumplio', $servicio->cumplio) == 1 ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('cumplio', $servicio->cumplio) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                        @error('cumplio')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="4" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $servicio->observaciones) }}</textarea>
                        @error('observaciones')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ url('/servicios') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </div>
</form>
@stop

@section('css')
<style>
    .card { border-radius: 14px; }
    .btn { border-radius: 10px; }
    label { font-weight: 600; }
    .form-control { border-radius: 10px; }
</style>
@stop

@section('js')
<script>
    function actualizarBloquesServicio() {
        let tipoServicio = ($('#tipo_servicio').val() || '').toUpperCase().trim();
        let categoriaRegistro = ($('#categoria_registro').val() || '').toUpperCase().trim();
        let asunto = $('#asunto');

        $('#bloque_busqueda').addClass('d-none');

        if (tipoServicio === 'BUSQUEDA') {
            $('#bloque_busqueda').removeClass('d-none');
        }

        if (!asunto.val().trim()) {
            if (categoriaRegistro && tipoServicio) {
                asunto.val(categoriaRegistro + ' DE ' + tipoServicio);
            } else if (tipoServicio) {
                asunto.val(tipoServicio);
            }
        }
    }

    $('#tipo_servicio, #categoria_registro').on('change', function () {
        actualizarBloquesServicio();
    });

    $(document).ready(function () {
        actualizarBloquesServicio();
    });
</script>
@stop

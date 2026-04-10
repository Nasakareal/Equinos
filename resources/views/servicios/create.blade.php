@extends('adminlte::page')

@section('title', 'Registrar Servicio')

@section('content_header')
    <h1>Registrar Servicio / Apoyo / Memorándum</h1>
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

<form action="{{ url('/servicios') }}" method="POST">
    @csrf

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Datos del servicio</h3>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de registro</label>
                        <select name="categoria_registro" id="categoria_registro" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO" {{ old('categoria_registro') == 'SERVICIO' ? 'selected' : '' }}>SERVICIO</option>
                            <option value="APOYO" {{ old('categoria_registro') == 'APOYO' ? 'selected' : '' }}>APOYO</option>
                            <option value="MEMORANDUM" {{ old('categoria_registro') == 'MEMORANDUM' ? 'selected' : '' }}>MEMORANDUM</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD" {{ old('tipo_servicio') == 'SEGURIDAD' ? 'selected' : '' }}>SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD" {{ old('tipo_servicio') == 'BARRIDOS DE SEGURIDAD' ? 'selected' : '' }}>BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA" {{ old('tipo_servicio') == 'BUSQUEDA' ? 'selected' : '' }}>BUSQUEDA</option>
                            <option value="DESFILES" {{ old('tipo_servicio') == 'DESFILES' ? 'selected' : '' }}>DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL" {{ old('tipo_servicio') == 'PROXIMIDAD SOCIAL' ? 'selected' : '' }}>PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS" {{ old('tipo_servicio') == 'ACTOS CIVICOS' ? 'selected' : '' }}>ACTOS CIVICOS</option>
                            <option value="OTRO" {{ old('tipo_servicio') == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número / Referencia</label>
                        <input type="text" name="folio_referencia" class="form-control" value="{{ old('folio_referencia') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time" name="hora" class="form-control" value="{{ old('hora') }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Municipio</label>
                        <input type="text" name="municipio" class="form-control" value="{{ old('municipio') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lugar</label>
                        <input type="text" name="lugar" class="form-control" value="{{ old('lugar') }}">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Asunto base</label>
                        <input type="text" name="asunto" id="asunto" class="form-control" value="{{ old('asunto') }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 d-none" id="bloque_busqueda">
                    <div class="form-group">
                        <label>Tipo de búsqueda</label>
                        <select name="tipo_busqueda" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="EN VIDA" {{ old('tipo_busqueda') == 'EN VIDA' ? 'selected' : '' }}>EN VIDA</option>
                            <option value="RECURSO HUMANO" {{ old('tipo_busqueda') == 'RECURSO HUMANO' ? 'selected' : '' }}>RECURSO HUMANO</option>
                            <option value="EXPLOSIVO" {{ old('tipo_busqueda') == 'EXPLOSIVO' ? 'selected' : '' }}>EXPLOSIVO</option>
                            <option value="FORENSE" {{ old('tipo_busqueda') == 'FORENSE' ? 'selected' : '' }}>FORENSE</option>
                            <option value="NARCOTICOS" {{ old('tipo_busqueda') == 'NARCOTICOS' ? 'selected' : '' }}>NARCOTICOS</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Elemento</label>
                        <select name="personal_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($personales as $p)
                                <option value="{{ $p->id }}" {{ old('personal_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Canino</label>
                        <select name="canino_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($caninos as $c)
                                <option value="{{ $c->id }}" {{ old('canino_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Equino</label>
                        <select name="equino_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($equinos as $e)
                                <option value="{{ $e->id }}" {{ old('equino_id') == $e->id ? 'selected' : '' }}>
                                    {{ $e->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Patrulla</label>
                        <select name="patrulla_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($patrullas as $p)
                                <option value="{{ $p->id }}" {{ old('patrulla_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre ?? $p->numero ?? $p->placas ?? 'ID '.$p->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>¿Se cumplió?</label>
                        <select name="cumplio" class="form-control">
                            <option value="1" {{ old('cumplio', '1') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('cumplio') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="3" class="form-control">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ url('/servicios') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
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

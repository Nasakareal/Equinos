@extends('adminlte::page')

@section('title', 'Registrar Servicio')

@section('content_header')
    <h1>Registrar Servicio / Apoyo / Memorándum</h1>
@stop

@section('content')
<form action="{{ url('/servicios') }}" method="POST">
    @csrf

    <div class="card card-outline card-primary shadow-sm">

        <div class="card-header">
            <h3 class="card-title">Datos del servicio</h3>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- TIPO REGISTRO --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de registro</label>
                        <select name="tipo_registro" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO">SERVICIO</option>
                            <option value="APOYO">APOYO</option>
                            <option value="MEMORANDUM">MEMORANDUM</option>
                        </select>
                    </div>
                </div>

                {{-- TIPO SERVICIO --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD">SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD">BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA">BUSQUEDA</option>
                            <option value="DESFILES">DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL">PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS">ACTOS CIVICOS</option>
                        </select>
                    </div>
                </div>

                {{-- FECHA --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>

                {{-- HORA --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time" name="hora" class="form-control" required>
                    </div>
                </div>

            </div>

            <hr>

            {{-- BLOQUES DINÁMICOS --}}
            <div class="row">

                {{-- BUSQUEDA --}}
                <div class="col-md-4 d-none" id="bloque_busqueda">
                    <div class="form-group">
                        <label>Tipo de búsqueda</label>
                        <select name="tipo_busqueda" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="EN VIDA">EN VIDA</option>
                            <option value="RECURSO HUMANO">RECURSO HUMANO</option>
                            <option value="EXPLOSIVO">EXPLOSIVO</option>
                            <option value="FORENSE">FORENSE</option>
                            <option value="NARCOTICOS">NARCOTICOS</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- SWITCHES --}}
            <div class="row mt-3">

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Seguridad</label><br>
                        <input type="checkbox" name="seguridad" value="1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Barridos</label><br>
                        <input type="checkbox" name="barridos_seguridad" value="1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Desfiles</label><br>
                        <input type="checkbox" name="desfiles" value="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Proximidad social</label><br>
                        <input type="checkbox" name="proximidad_social" value="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Actos cívicos</label><br>
                        <input type="checkbox" name="actos_civicos" value="1">
                    </div>
                </div>

            </div>

            <hr>

            {{-- RECURSOS --}}
            <div class="row">

                {{-- PERSONAL --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Elemento</label>
                        <select name="personal_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($personales as $p)
                                <option value="{{ $p->id }}">{{ $p->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- CANINO --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Canino</label>
                        <select name="canino_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($caninos as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- EQUINO --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Equino</label>
                        <select name="equino_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($equinos as $e)
                                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- PATRULLA --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Patrulla</label>
                        <select name="patrulla_id" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($patrullas as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nombre ?? $p->numero ?? $p->placas ?? 'ID '.$p->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            {{-- CUMPLIO --}}
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>¿Se cumplió?</label>
                        <select name="cumplio" class="form-control">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- OBSERVACIONES --}}
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ url('/servicios') }}" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>

    </div>
</form>
@stop

@section('js')
<script>
    $('#tipo_servicio').on('change', function () {
        let val = $(this).val();

        $('#bloque_busqueda').addClass('d-none');

        if (val === 'BUSQUEDA') {
            $('#bloque_busqueda').removeClass('d-none');
        }
    });
</script>
@stop

@extends('adminlte::page')

@section('title', 'Nuevo Reporte')

@section('content_header')
    <h1>Nuevo Reporte Operativo</h1>
@stop

@section('content')
    <form action="{{ route('mis_servicios.reportes.store', $servicio->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Datos base del servicio</h3>
                        <div class="card-tools">
                            <a href="{{ route('mis_servicios.show', $servicio->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label><strong>Fecha del servicio</strong></label>
                                <input type="text" class="form-control" value="{{ optional($servicio->fecha)->format('d/m/Y') }}" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Hora</strong></label>
                                <input type="text" class="form-control" value="{{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Tipo de servicio</strong></label>
                                <input type="text" class="form-control" value="{{ $servicio->tipo_servicio ?? '-' }}" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Municipio</strong></label>
                                <input type="text" class="form-control" value="{{ $servicio->municipio ?? '-' }}" readonly>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label><strong>Asunto base</strong></label>
                                <input type="text" class="form-control" value="{{ $servicio->asunto ?? '-' }}" readonly>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label><strong>Lugar base</strong></label>
                                <input type="text" class="form-control" value="{{ $servicio->lugar ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Revisa esto:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Captura del reporte</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_reporte">Tipo de reporte</label>
                                    <select name="tipo_reporte" id="tipo_reporte" class="form-control @error('tipo_reporte') is-invalid @enderror" required>
                                        <option value="">Seleccione...</option>
                                        <option value="INICIO" {{ old('tipo_reporte') == 'INICIO' ? 'selected' : '' }}>INICIO</option>
                                        <option value="CONTINUIDAD" {{ old('tipo_reporte') == 'CONTINUIDAD' ? 'selected' : '' }}>CONTINUIDAD</option>
                                        <option value="FINALIZACION" {{ old('tipo_reporte') == 'FINALIZACION' ? 'selected' : '' }}>FINALIZACIÓN</option>
                                        <option value="INCIDENTE" {{ old('tipo_reporte') == 'INCIDENTE' ? 'selected' : '' }}>INCIDENTE</option>
                                        <option value="RESULTADO" {{ old('tipo_reporte') == 'RESULTADO' ? 'selected' : '' }}>RESULTADO</option>
                                        <option value="PUESTA_DISPOSICION" {{ old('tipo_reporte') == 'PUESTA_DISPOSICION' ? 'selected' : '' }}>PUESTA A DISPOSICIÓN</option>
                                        <option value="APOYO_BUSQUEDA" {{ old('tipo_reporte') == 'APOYO_BUSQUEDA' ? 'selected' : '' }}>APOYO BÚSQUEDA</option>
                                        <option value="EVENTO" {{ old('tipo_reporte') == 'EVENTO' ? 'selected' : '' }}>EVENTO</option>
                                        <option value="OTRO" {{ old('tipo_reporte') == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                                    </select>
                                    @error('tipo_reporte')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', optional($servicio->fecha)->format('Y-m-d')) }}" required>
                                    @error('fecha')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hora">Hora</label>
                                    <input type="time" name="hora" id="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora', $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '') }}">
                                    @error('hora')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="municipio">Municipio</label>
                                    <input type="text" name="municipio" id="municipio" class="form-control @error('municipio') is-invalid @enderror" value="{{ old('municipio', $servicio->municipio) }}">
                                    @error('municipio')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asunto">Asunto</label>
                                    <input type="text" name="asunto" id="asunto" class="form-control @error('asunto') is-invalid @enderror" value="{{ old('asunto', $servicio->asunto) }}">
                                    @error('asunto')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lugar">Lugar</label>
                                    <input type="text" name="lugar" id="lugar" class="form-control @error('lugar') is-invalid @enderror" value="{{ old('lugar', $servicio->lugar) }}">
                                    @error('lugar')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lat">Latitud</label>
                                    <input type="number" step="0.0000001" name="lat" id="lat" class="form-control @error('lat') is-invalid @enderror" value="{{ old('lat', $servicio->lat) }}">
                                    @error('lat')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lng">Longitud</label>
                                    <input type="number" step="0.0000001" name="lng" id="lng" class="form-control @error('lng') is-invalid @enderror" value="{{ old('lng', $servicio->lng) }}">
                                    @error('lng')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="narrativa">Narrativa</label>
                                    <textarea name="narrativa" id="narrativa" rows="6" class="form-control @error('narrativa') is-invalid @enderror">{{ old('narrativa') }}</textarea>
                                    @error('narrativa')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="estado_fuerza_texto">Estado de fuerza</label>
                                    <textarea name="estado_fuerza_texto" id="estado_fuerza_texto" rows="4" class="form-control @error('estado_fuerza_texto') is-invalid @enderror">{{ old('estado_fuerza_texto') }}</textarea>
                                    @error('estado_fuerza_texto')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="acciones_a_realizar">Acciones a realizar</label>
                                    <textarea name="acciones_a_realizar" id="acciones_a_realizar" rows="4" class="form-control @error('acciones_a_realizar') is-invalid @enderror">{{ old('acciones_a_realizar') }}</textarea>
                                    @error('acciones_a_realizar')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="acciones_realizadas">Acciones realizadas</label>
                                    <textarea name="acciones_realizadas" id="acciones_realizadas" rows="4" class="form-control @error('acciones_realizadas') is-invalid @enderror">{{ old('acciones_realizadas') }}</textarea>
                                    @error('acciones_realizadas')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="resultados">Resultados</label>
                                    <textarea name="resultados" id="resultados" rows="4" class="form-control @error('resultados') is-invalid @enderror">{{ old('resultados') }}</textarea>
                                    @error('resultados')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12" id="bloque_datos_persona">
                                <div class="form-group">
                                    <label for="datos_persona_asegurada">Datos de la persona asegurada</label>
                                    <textarea name="datos_persona_asegurada" id="datos_persona_asegurada" rows="5" class="form-control @error('datos_persona_asegurada') is-invalid @enderror">{{ old('datos_persona_asegurada') }}</textarea>
                                    @error('datos_persona_asegurada')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="conclusion">Conclusión / cierre</label>
                                    <textarea name="conclusion" id="conclusion" rows="4" class="form-control @error('conclusion') is-invalid @enderror">{{ old('conclusion') }}</textarea>
                                    @error('conclusion')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h5>Evidencia fotográfica</h5>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fotos">Fotos</label>
                                    <input type="file" name="fotos[]" id="fotos" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted">Puedes seleccionar varias imágenes.</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="contenedor_descripciones_fotos" class="row"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('mis_servicios.show', $servicio->id) }}" class="btn btn-secondary">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
<script>
    function toggleDatosPersona() {
        const tipo = document.getElementById('tipo_reporte').value;
        const bloque = document.getElementById('bloque_datos_persona');

        if (tipo === 'PUESTA_DISPOSICION' || tipo === 'INCIDENTE') {
            bloque.style.display = '';
        } else {
            bloque.style.display = 'none';
        }
    }

    function renderDescripcionesFotos() {
        const input = document.getElementById('fotos');
        const contenedor = document.getElementById('contenedor_descripciones_fotos');
        contenedor.innerHTML = '';

        Array.from(input.files).forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';

            col.innerHTML = `
                <div class="form-group">
                    <label>Descripción para: ${file.name}</label>
                    <input type="text" name="descripcion[${index}]" class="form-control" maxlength="255" placeholder="Descripción opcional de la foto">
                </div>
            `;

            contenedor.appendChild(col);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleDatosPersona();

        document.getElementById('tipo_reporte').addEventListener('change', toggleDatosPersona);
        document.getElementById('fotos').addEventListener('change', renderDescripcionesFotos);
    });
</script>
@stop

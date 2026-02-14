@extends('adminlte::page')

@section('title', 'Nueva Asignación')

@section('content_header')
    <h1>Nueva Asignación de Unidad</h1>
    <p class="text-muted mb-0">Registro operativo diario para Equinos y Caninos</p>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Datos del Servicio
                </h3>
            </div>

            <div class="card-body">

                <form action="{{ route('patrullas_asignaciones.store') }}" method="POST" id="frmAsignacion">
                    @csrf

                    <input type="hidden" name="turno_id" value="{{ (int)$turno_id }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unidad</label>
                                <select name="patrol_id" class="form-control @error('patrol_id') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($patrols as $p)
                                        <option value="{{ $p->id }}" {{ old('patrol_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->numero_economico }} ({{ $p->tipo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patrol_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date"
                                       id="fecha_asignacion"
                                       name="fecha"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       value="{{ old('fecha', $fecha ?? date('Y-m-d')) }}"
                                       required>
                                @error('fecha')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Al cambiar la fecha se actualiza la lista de personal.</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Turno en servicio</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $turno ? ($turno->clave . ' - ' . $turno->nombre) : 'Sin turno' }}"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Servicio</label>
                                <input type="text"
                                       name="servicio"
                                       class="form-control @error('servicio') is-invalid @enderror"
                                       value="{{ old('servicio') }}"
                                       placeholder="Ej. Recorridos de prevención, Supervisión...">
                                @error('servicio')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Zona / Cuadrante</label>
                                <input type="text"
                                       name="zona"
                                       class="form-control @error('zona') is-invalid @enderror"
                                       value="{{ old('zona') }}"
                                       placeholder="Ej. Cerro, Plaza mando, Sector...">
                                @error('zona')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fa-solid fa-users"></i>
                                Personal Asignado (arrastrable por botones)
                            </h3>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Disponible</label>
                                    <input type="text" id="buscadorPersonal" class="form-control mb-2" placeholder="Buscar...">
                                    <select id="lstDisponibles" class="form-control" size="12">
                                        @foreach($personals as $per)
                                            <option value="{{ $per->id }}">{{ $per->nombres }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Doble click para mover.</small>
                                </div>

                                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 w-100" id="btnToEnc">→ Enc</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-3 w-100" id="btnToAgr">→ Agr</button>

                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-2 w-100" id="btnFromEnc">← Enc</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="btnFromAgr">← Agr</button>
                                </div>

                                <div class="col-md-3">
                                    <label>Encargado</label>
                                    <select id="lstEncargado" class="form-control" size="12"></select>
                                    <input type="hidden" name="encargado_id" id="encargado_id" value="">
                                    <small class="text-muted">Solo 1 encargado.</small>
                                </div>

                                <div class="col-md-3">
                                    <label>Agregados</label>
                                    <select id="lstAgregados" class="form-control" size="12" multiple></select>
                                    <div id="agregadosHidden"></div>
                                    <small class="text-muted">Varios agregados.</small>
                                </div>
                            </div>

                            <p class="text-danger mt-3 mb-0">
                                ⚠️ Encargado y agregados salen de aquí; ya no hay “personals[]” duplicado.
                            </p>

                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones"
                                  rows="3"
                                  class="form-control @error('observaciones') is-invalid @enderror"
                                  placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <hr>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="fa-solid fa-check"></i> Guardar Asignación
                        </button>

                        <a href="{{ route('patrullas_asignaciones.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-ban"></i> Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@stop

@section('css')
<style>
    .card { border-radius: 15px; }
    select.form-control { background-color: #fff !important; color: #000 !important; }
    select.form-control option { background-color: #fff !important; color: #000 !important; }
    select.form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.15rem rgba(0,123,255,.25); }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fecha = document.getElementById('fecha_asignacion');
        if (fecha) {
            fecha.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('fecha', this.value);
                window.location.href = url.toString();
            });
        }

        const disponibles = document.getElementById('lstDisponibles');
        const encargado = document.getElementById('lstEncargado');
        const agregados = document.getElementById('lstAgregados');

        const encargadoId = document.getElementById('encargado_id');
        const agregadosHidden = document.getElementById('agregadosHidden');

        const btnToEnc = document.getElementById('btnToEnc');
        const btnToAgr = document.getElementById('btnToAgr');
        const btnFromEnc = document.getElementById('btnFromEnc');
        const btnFromAgr = document.getElementById('btnFromAgr');

        const buscador = document.getElementById('buscadorPersonal');

        function optionFrom(opt) {
            const o = document.createElement('option');
            o.value = opt.value;
            o.text = opt.text;
            return o;
        }

        function removeSelected(select) {
            const selected = Array.from(select.selectedOptions);
            selected.forEach(o => o.remove());
            return selected;
        }

        function refreshHiddenInputs() {
            encargadoId.value = encargado.options.length ? encargado.options[0].value : '';

            agregadosHidden.innerHTML = '';
            Array.from(agregados.options).forEach(o => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'agregados[]';
                input.value = o.value;
                agregadosHidden.appendChild(input);
            });
        }

        function moveToEncargado() {
            const sel = Array.from(disponibles.selectedOptions);
            if (!sel.length) return;

            if (encargado.options.length) {
                const prev = encargado.options[0];
                disponibles.appendChild(optionFrom(prev));
                encargado.innerHTML = '';
            }

            const first = sel[0];
            encargado.appendChild(optionFrom(first));
            first.remove();

            sel.slice(1).forEach(o => {
                agregados.appendChild(optionFrom(o));
                o.remove();
            });

            refreshHiddenInputs();
        }

        function moveToAgregados() {
            const sel = Array.from(disponibles.selectedOptions);
            if (!sel.length) return;

            sel.forEach(o => {
                agregados.appendChild(optionFrom(o));
                o.remove();
            });

            refreshHiddenInputs();
        }

        function removeFromEncargado() {
            if (!encargado.options.length) return;
            const o = encargado.options[0];
            disponibles.appendChild(optionFrom(o));
            encargado.innerHTML = '';
            refreshHiddenInputs();
        }

        function removeFromAgregados() {
            const sel = removeSelected(agregados);
            if (!sel.length) return;
            sel.forEach(o => disponibles.appendChild(optionFrom(o)));
            refreshHiddenInputs();
        }

        btnToEnc.addEventListener('click', moveToEncargado);
        btnToAgr.addEventListener('click', moveToAgregados);
        btnFromEnc.addEventListener('click', removeFromEncargado);
        btnFromAgr.addEventListener('click', removeFromAgregados);

        disponibles.addEventListener('dblclick', function () {
            moveToAgregados();
        });

        agregados.addEventListener('dblclick', function () {
            removeFromAgregados();
        });

        encargado.addEventListener('dblclick', function () {
            removeFromEncargado();
        });

        buscador.addEventListener('input', function () {
            const q = (this.value || '').toLowerCase().trim();
            Array.from(disponibles.options).forEach(o => {
                const show = o.text.toLowerCase().includes(q);
                o.hidden = !show;
            });
        });

        document.getElementById('frmAsignacion').addEventListener('submit', function (e) {
            refreshHiddenInputs();
        });

        refreshHiddenInputs();

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
    });
</script>
@stop

@extends('adminlte::page')

@section('title', 'Crear Personal')

@section('content_header')
    <h1>Creación de Personal</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los Datos</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('personal.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id">Usuario del sistema (opcional)</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="" selected>Sin usuario</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }} ({{ $u->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombres">Nombre completo</label>
                                    <input type="text"
                                           name="nombres"
                                           id="nombres"
                                           class="form-control @error('nombres') is-invalid @enderror"
                                           value="{{ old('nombres') }}"
                                           placeholder="Ingrese el nombre completo"
                                           required>
                                    @error('nombres')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grado">Grado</label>
                                    <input type="text"
                                           name="grado"
                                           id="grado"
                                           class="form-control @error('grado') is-invalid @enderror"
                                           value="{{ old('grado') }}"
                                           placeholder="Ej. Oficial, Sgto., etc.">
                                    @error('grado')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_empleado">No. empleado</label>
                                    <input type="text"
                                           name="no_empleado"
                                           id="no_empleado"
                                           class="form-control @error('no_empleado') is-invalid @enderror"
                                           value="{{ old('no_empleado') }}"
                                           placeholder="Ingrese el número de empleado">
                                    @error('no_empleado')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cuip">CUIP</label>
                                    <input type="text"
                                           name="cuip"
                                           id="cuip"
                                           class="form-control @error('cuip') is-invalid @enderror"
                                           value="{{ old('cuip') }}"
                                           placeholder="Ingrese el CUIP">
                                    @error('cuip')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="crp">CRP</label>
                                    <input type="text"
                                           name="crp"
                                           id="crp"
                                           class="form-control @error('crp') is-invalid @enderror"
                                           value="{{ old('crp') }}"
                                           placeholder="Ingrese el CRP">
                                    @error('crp')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dependencia">Dependencia</label>
                                    <input type="text"
                                           name="dependencia"
                                           id="dependencia"
                                           class="form-control @error('dependencia') is-invalid @enderror"
                                           value="{{ old('dependencia') }}"
                                           placeholder="Ej. SSP / Dirección / etc.">
                                    @error('dependencia')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id">Área</label>
                                    <select name="area_id" id="area_id" class="form-control @error('area_id') is-invalid @enderror">
                                        <option value="" selected>Sin área</option>
                                        @foreach ($areas as $a)
                                            <option value="{{ $a->id }}" {{ old('area_id') == $a->id ? 'selected' : '' }}>
                                                {{ $a->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="turno_id">Turno</label>
                                    <select name="turno_id" id="turno_id" class="form-control @error('turno_id') is-invalid @enderror">
                                        <option value="" selected>Sin turno</option>
                                        @foreach ($turnos as $t)
                                            <option value="{{ $t->id }}" {{ old('turno_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->clave }} - {{ $t->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('turno_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="text-muted">Si seleccionas MIXTO, el horario se definirá después en su horario personal.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="celular">Celular</label>
                                    <input type="text"
                                           name="celular"
                                           id="celular"
                                           class="form-control @error('celular') is-invalid @enderror"
                                           value="{{ old('celular') }}"
                                           placeholder="10 dígitos">
                                    @error('celular')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <input type="text"
                                           name="cargo"
                                           id="cargo"
                                           class="form-control @error('cargo') is-invalid @enderror"
                                           value="{{ old('cargo') }}"
                                           placeholder="Ej. Perito, Encargado, Operador, etc.">
                                    @error('cargo')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="actividad">Actividad</label>
                                    <select name="actividad" id="actividad" class="form-control @error('actividad') is-invalid @enderror">
                                        <option value="" selected>Seleccione una actividad</option>
                                        <option value="Veterinario" {{ old('actividad') == 'Veterinario' ? 'selected' : '' }}>Veterinario</option>
                                        <option value="Cuartelero" {{ old('actividad') == 'Cuartelero' ? 'selected' : '' }}>Cuartelero</option>
                                        <option value="Arrendador" {{ old('actividad') == 'Arrendador' ? 'selected' : '' }}>Arrendador</option>
                                        <option value="Administrativo" {{ old('actividad') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                        <option value="Operativo" {{ old('actividad') == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                                        <option value="Instructor Canino" {{ old('actividad') == 'Instructor Canino' ? 'selected' : '' }}>Instructor Canino</option>
                                        <option value="Terapeuta" {{ old('actividad') == 'Terapeuta' ? 'selected' : '' }}>Terapeuta</option>
                                    </select>
                                    @error('actividad')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_patrullaje">Área de patrullaje</label>
                                    <input type="text"
                                           name="area_patrullaje"
                                           id="area_patrullaje"
                                           class="form-control @error('area_patrullaje') is-invalid @enderror"
                                           value="{{ old('area_patrullaje') }}"
                                           placeholder="Ej. Sector, Zona, Región, etc.">
                                    @error('area_patrullaje')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="es_responsable">¿Es responsable?</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="es_responsable"
                                               name="es_responsable"
                                               value="1"
                                               {{ old('es_responsable') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="es_responsable">Sí</label>
                                    </div>
                                    @error('es_responsable')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="siempre_visible">Siempre visible</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="siempre_visible"
                                               name="siempre_visible"
                                               value="1"
                                               {{ old('siempre_visible') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="siempre_visible">Sí</label>
                                    </div>
                                    @error('siempre_visible')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="activo">Estatus</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="activo"
                                               name="activo"
                                               value="1"
                                               {{ old('activo', 1) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="activo">Activo</label>
                                    </div>
                                    @error('activo')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones"
                                              id="observaciones"
                                              rows="3"
                                              class="form-control @error('observaciones') is-invalid @enderror"
                                              placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                                    @error('observaciones')
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
                                    <a href="{{ route('personal.index') }}" class="btn btn-secondary">
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

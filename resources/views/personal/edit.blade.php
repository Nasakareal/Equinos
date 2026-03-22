@extends('adminlte::page')

@section('title', 'Editar Personal')

@section('content_header')
    <h1>Edición de Personal</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Modificar Datos</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('personal.update', $personal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id">Usuario del sistema</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="">Sin usuario</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}" {{ old('user_id', $personal->user_id) == $u->id ? 'selected' : '' }}>
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
                                           value="{{ old('nombres', $personal->nombres) }}"
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
                                           value="{{ old('grado', $personal->grado) }}">
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
                                           value="{{ old('no_empleado', $personal->no_empleado) }}">
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
                                           value="{{ old('cuip', $personal->cuip) }}">
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
                                           value="{{ old('crp', $personal->crp) }}">
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
                                           value="{{ old('dependencia', $personal->dependencia) }}">
                                    @error('dependencia')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id">Área</label>
                                    <select name="area_id" id="area_id" class="form-control @error('area_id') is-invalid @enderror">
                                        <option value="">Sin área</option>
                                        @foreach ($areas as $a)
                                            <option value="{{ $a->id }}" {{ old('area_id', $personal->area_id) == $a->id ? 'selected' : '' }}>
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
                                        <option value="">Sin turno</option>
                                        @foreach ($turnos as $t)
                                            <option value="{{ $t->id }}" {{ (string) old('turno_id', $personal->turno_id) === (string) $t->id ? 'selected' : '' }}>
                                                {{ $t->clave }} - {{ $t->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('turno_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="text-muted">Si seleccionas MIXTO, el horario se configura aparte en el horario personal.</small>
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
                                           value="{{ old('celular', $personal->celular) }}">
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
                                           value="{{ old('cargo', $personal->cargo) }}">
                                    @error('cargo')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="actividad">Actividad</label>
                                    <select name="actividad" id="actividad" class="form-control @error('actividad') is-invalid @enderror">
                                        <option value="">Seleccione una actividad</option>
                                        <option value="Veterinario" {{ old('actividad', $personal->actividad) == 'Veterinario' ? 'selected' : '' }}>Veterinario</option>
                                        <option value="Cuartelero" {{ old('actividad', $personal->actividad) == 'Cuartelero' ? 'selected' : '' }}>Cuartelero</option>
                                        <option value="Arrendador" {{ old('actividad', $personal->actividad) == 'Arrendador' ? 'selected' : '' }}>Arrendador</option>
                                        <option value="Administrativo" {{ old('actividad', $personal->actividad) == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                        <option value="Operativo" {{ old('actividad', $personal->actividad) == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                                        <option value="Instructor Canino" {{ old('actividad', $personal->actividad) == 'Instructor Canino' ? 'selected' : '' }}>Instructor Canino</option>
                                        <option value="Terapeuta" {{ old('actividad', $personal->actividad) == 'Terapeuta' ? 'selected' : '' }}>Terapeuta</option>
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
                                           value="{{ old('area_patrullaje', $personal->area_patrullaje) }}">
                                    @error('area_patrullaje')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="es_responsable">¿Es responsable?</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="es_responsable"
                                               name="es_responsable"
                                               value="1"
                                               {{ old('es_responsable', $personal->es_responsable) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="es_responsable">Sí</label>
                                    </div>
                                    @error('es_responsable')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="siempre_visible">Siempre visible</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="siempre_visible"
                                               name="siempre_visible"
                                               value="1"
                                               {{ old('siempre_visible', $personal->siempre_visible ?? 0) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="siempre_visible">Sí</label>
                                    </div>
                                    @error('siempre_visible')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="activo">Estatus</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="activo"
                                               name="activo"
                                               value="1"
                                               {{ old('activo', $personal->activo) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="activo">Activo</label>
                                    </div>
                                    @error('activo')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones"
                                              id="observaciones"
                                              rows="3"
                                              class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $personal->observaciones) }}</textarea>
                                    @error('observaciones')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-save"></i> Guardar cambios
                                </button>
                                <a href="{{ route('personal.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-ban"></i> Cancelar
                                </a>
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

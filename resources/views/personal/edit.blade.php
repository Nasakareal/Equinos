{{-- resources/views/personal/edit.blade.php --}}

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
                            <!-- Usuario -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id">Usuario del sistema</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="">Sin usuario</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}"
                                                {{ old('user_id', $personal->user_id) == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }} ({{ $u->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nombres -->
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

                            <!-- Grado -->
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
                            <!-- No empleado -->
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

                            <!-- CUIP -->
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

                            <!-- CRP -->
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
                            <!-- Dependencia -->
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

                            <!-- Celular -->
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

                            <!-- Cargo -->
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
                        </div>

                        <div class="row">
                            <!-- Área patrullaje -->
                            <div class="col-md-6">
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

                            <!-- Responsable -->
                            <div class="col-md-3">
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
                                </div>
                            </div>

                            <!-- Activo -->
                            <div class="col-md-3">
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
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Observaciones -->
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

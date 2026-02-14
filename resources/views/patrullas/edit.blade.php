@extends('adminlte::page')

@section('title', 'Editar Patrulla')

@section('content_header')
    <h1>Editar Unidad / Patrulla</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Actualizar Datos</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('patrullas.update', $patrol->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Número Económico -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero_economico">Número Económico / CRP</label>
                                    <input type="text"
                                           name="numero_economico"
                                           id="numero_economico"
                                           class="form-control @error('numero_economico') is-invalid @enderror"
                                           value="{{ old('numero_economico', $patrol->numero_economico) }}"
                                           required>
                                    @error('numero_economico')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo de Unidad</label>
                                    <select name="tipo"
                                            id="tipo"
                                            class="form-control @error('tipo') is-invalid @enderror"
                                            required>
                                        <option value="RAM" {{ old('tipo', $patrol->tipo) == 'RAM' ? 'selected' : '' }}>RAM / Patrulla</option>
                                        <option value="EQUINO" {{ old('tipo', $patrol->tipo) == 'EQUINO' ? 'selected' : '' }}>Equino</option>
                                        <option value="CANINO" {{ old('tipo', $patrol->tipo) == 'CANINO' ? 'selected' : '' }}>Canino</option>
                                        <option value="LOGISTICA" {{ old('tipo', $patrol->tipo) == 'LOGISTICA' ? 'selected' : '' }}>Logística / Remolque</option>
                                    </select>
                                    @error('tipo')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado"
                                            id="estado"
                                            class="form-control @error('estado') is-invalid @enderror"
                                            required>
                                        <option value="ACTIVO" {{ old('estado', $patrol->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                        <option value="TALLER" {{ old('estado', $patrol->estado) == 'TALLER' ? 'selected' : '' }}>TALLER</option>
                                        <option value="BAJA" {{ old('estado', $patrol->estado) == 'BAJA' ? 'selected' : '' }}>BAJA</option>
                                    </select>
                                    @error('estado')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Placas -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="placas">Placas</label>
                                    <input type="text"
                                           name="placas"
                                           id="placas"
                                           class="form-control @error('placas') is-invalid @enderror"
                                           value="{{ old('placas', $patrol->placas) }}">
                                    @error('placas')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Marca -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="marca">Marca</label>
                                    <input type="text"
                                           name="marca"
                                           id="marca"
                                           class="form-control @error('marca') is-invalid @enderror"
                                           value="{{ old('marca', $patrol->marca) }}">
                                    @error('marca')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="modelo">Modelo</label>
                                    <input type="text"
                                           name="modelo"
                                           id="modelo"
                                           class="form-control @error('modelo') is-invalid @enderror"
                                           value="{{ old('modelo', $patrol->modelo) }}">
                                    @error('modelo')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Año -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <input type="text"
                                           name="anio"
                                           id="anio"
                                           class="form-control @error('anio') is-invalid @enderror"
                                           value="{{ old('anio', $patrol->anio) }}">
                                    @error('anio')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Color -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text"
                                           name="color"
                                           id="color"
                                           class="form-control @error('color') is-invalid @enderror"
                                           value="{{ old('color', $patrol->color) }}">
                                    @error('color')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <input type="text"
                                           name="observaciones"
                                           id="observaciones"
                                           class="form-control @error('observaciones') is-invalid @enderror"
                                           value="{{ old('observaciones', $patrol->observaciones) }}">
                                    @error('observaciones')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-check"></i> Guardar Cambios
                                </button>

                                <a href="{{ route('patrullas.index') }}" class="btn btn-secondary">
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

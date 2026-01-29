{{-- resources/views/armamento/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Editar Armamento')

@section('content_header')
    <h1>Edición de Armamento</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modifique los Datos</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('armamento.update', $weapon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Tipo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select name="tipo"
                                            id="tipo"
                                            class="form-control @error('tipo') is-invalid @enderror"
                                            required>
                                        <option value="" disabled>Seleccione...</option>
                                        <option value="CORTA" {{ old('tipo', $weapon->tipo) === 'CORTA' ? 'selected' : '' }}>CORTA</option>
                                        <option value="LARGA" {{ old('tipo', $weapon->tipo) === 'LARGA' ? 'selected' : '' }}>LARGA</option>
                                    </select>
                                    @error('tipo')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Matrícula -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="matricula">Matrícula</label>
                                    <input type="text"
                                           name="matricula"
                                           id="matricula"
                                           class="form-control @error('matricula') is-invalid @enderror"
                                           value="{{ old('matricula', $weapon->matricula) }}"
                                           placeholder="Ingrese la matrícula"
                                           required>
                                    @error('matricula')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="text-muted">Debe seguir siendo única.</small>
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
                                        <option value="" disabled>Seleccione...</option>
                                        <option value="ACTIVA" {{ old('estado', $weapon->estado) === 'ACTIVA' ? 'selected' : '' }}>ACTIVA</option>
                                        <option value="INACTIVA" {{ old('estado', $weapon->estado) === 'INACTIVA' ? 'selected' : '' }}>INACTIVA</option>
                                        <option value="BAJA" {{ old('estado', $weapon->estado) === 'BAJA' ? 'selected' : '' }}>BAJA</option>
                                    </select>
                                    @error('estado')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Marca / Modelo -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="marca_modelo">Marca / Modelo (opcional)</label>
                                    <input type="text"
                                           name="marca_modelo"
                                           id="marca_modelo"
                                           class="form-control @error('marca_modelo') is-invalid @enderror"
                                           value="{{ old('marca_modelo', $weapon->marca_modelo) }}"
                                           placeholder="Ej. GLOCK 17 / BERETTA / REMINGTON 870">
                                    @error('marca_modelo')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Observaciones -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones (opcional)</label>
                                    <textarea name="observaciones"
                                              id="observaciones"
                                              rows="3"
                                              class="form-control @error('observaciones') is-invalid @enderror"
                                              placeholder="Notas adicionales...">{{ old('observaciones', $weapon->observaciones) }}</textarea>
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
                                        <i class="fa-solid fa-check"></i> Guardar cambios
                                    </button>
                                    <a href="{{ route('armamento.show', $weapon->id) }}" class="btn btn-secondary">
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
        .form-group label {
            font-weight: bold;
        }
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

{{-- resources/views/admin/settings/users/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Edición de Usuario</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Modificar Datos</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nombre del Usuario</label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Área -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area">Área</label>
                                    <input type="text"
                                           name="area"
                                           id="area"
                                           class="form-control @error('area') is-invalid @enderror"
                                           value="{{ old('area', $user->area) }}"
                                           placeholder="Área (opcional)">
                                    @error('area')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Rol -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Rol</label>
                                    @php
                                        $roleActual = old('role', $user->roles->pluck('name')->first());
                                    @endphp
                                    <select name="role"
                                            id="role"
                                            class="form-control @error('role') is-invalid @enderror"
                                            required>
                                        <option value="" disabled {{ $roleActual ? '' : 'selected' }}>Seleccione un rol</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ $roleActual === $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror

                                    @if($user->roles->pluck('name')->first() === 'Superadmin')
                                        <small class="text-muted">
                                            Este usuario es Superadmin. Si intentas cambiar el rol y es el último, el sistema lo bloqueará.
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Contraseña (opcional) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Nueva Contraseña (opcional)</label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Deja vacío para no cambiar">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirmation">Repetir Contraseña</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control"
                                           placeholder="Confirme la contraseña (si la cambias)">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-save"></i> Guardar cambios
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
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
            if (typeof Swal !== 'undefined') {
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
            }
        @endif
    </script>
@stop

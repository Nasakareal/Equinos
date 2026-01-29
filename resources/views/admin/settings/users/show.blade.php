{{-- resources/views/admin/settings/users/show.blade.php --}}

@extends('adminlte::page')

@section('title', 'Detalles del Usuario')

@section('content_header')
    <h1>Detalles del Usuario</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Información del Usuario</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <p class="form-control-static">{{ $user->name }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <p class="form-control-static">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Área -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Área</label>
                                <p class="form-control-static">
                                    {{ $user->area ?? 'No especificada' }}
                                </p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <p class="form-control-static">
                                    {{ $user->estado }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rol -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Rol asignado</label>
                                <p class="form-control-static">
                                    {{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol asignado' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Foto de perfil -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Foto de perfil</label>

                                @if ($user->foto_perfil)
                                    <div>
                                        <img
                                            src="{{ asset('storage/' . $user->foto_perfil) }}"
                                            alt="Foto de perfil"
                                            class="img-thumbnail"
                                            style="max-width: 150px;"
                                        >
                                    </div>
                                @else
                                    <p class="form-control-static">No tiene foto de perfil.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
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
        .form-control-static {
            display: block;
            font-size: 1rem;
            margin-top: 0.4rem;
        }
        .img-thumbnail {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 4px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista show de usuario cargada correctamente');
    </script>
@stop

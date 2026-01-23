@extends('adminlte::page')

@section('title', 'Detalles del Servicio')

@section('content_header')
    <h1>Detalles del Servicio</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos Registrados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tipo de Vehículo -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_vehiculo">Tipo de Vehículo</label>
                                <p class="form-control-static">{{ $servicio->tipo_vehiculo }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Aseguradora -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="aseguradora">Aseguradora</label>
                                <p class="form-control-static">{{ $servicio->aseguradora ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Descripción -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <p class="form-control-static">{{ $servicio->descripcion }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Fecha de Registro -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_creacion">Fecha de Registro</label>
                                <p class="form-control-static">{{ $servicio->created_at->format('d-m-Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($servicio->foto_vehiculo)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Foto del Vehículo</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $servicio->foto_vehiculo) }}" alt="Foto del Vehículo" class="img-thumbnail" width="300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="row">
                        <!-- Botón de regreso -->
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <a href="{{ route('servicios.index', $grua->id) }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left"></i> Volver
                                </a>
                            </div>
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
            margin-top: 0.5rem;
        }
        .img-thumbnail {
            border: 1px solid #dee2e6;
            padding: 4px;
            max-height: 300px;
            object-fit: cover;
        }
    </style>
@stop

@section('js')
    <script> console.log("Vista de detalles del servicio cargada correctamente."); </script>
@stop

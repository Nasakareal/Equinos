@extends('adminlte::page')

@section('title', 'Reportes Diarios')

@section('content_header')
    <h1>Reportes Diarios</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Consultas por tipo de reporte</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success mb-2">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-2">{{ session('error') }}</div>
                @endif

                <div class="row">

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Estado de Fuerza</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('daily_reports.estado_fuerza.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Personal</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('daily_reports.lista_personal.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Pase de Lista Canina</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('daily_reports.pase_lista_canina.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Pase de Lista Agrupamiento Equinos y Caninos</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('daily_reports.pase_lista_agrupamiento_equinos_caninos.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Armamento Equinos y Caninos</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('daily_reports.armamento_equinos_caninos.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@stop

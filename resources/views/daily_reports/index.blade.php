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
                <h3 class="card-title">Descargas</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success mb-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-2">{{ session('error') }}</div>
                @endif

                <form method="GET" action="{{ route('daily_reports.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" value="{{ $fecha }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Turno</label>
                            <input type="number" name="turno_id" value="{{ $turno_id }}" class="form-control" min="1">
                        </div>

                        <div class="col-md-6 d-flex align-items-end" style="gap:8px;">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fa-solid fa-filter"></i> Ver
                            </button>

                            @can('crear reportes')
                                <form action="{{ route('daily_reports.generar') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="fecha" value="{{ $fecha }}">
                                    <input type="hidden" name="turno_id" value="{{ $turno_id }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa-solid fa-bolt"></i> Generar todos
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </form>

                <div class="row">
                    @foreach($tipos as $t)
                        @php
                            $tipo = $t['tipo'];
                            $exists = $estado[$tipo]['exists'] ?? false;
                        @endphp

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-outline {{ $exists ? 'card-success' : 'card-secondary' }}">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{ $t['label'] }}
                                    </h3>
                                    <div class="card-tools">
                                        @if($exists)
                                            <span class="badge badge-success">Listo</span>
                                        @else
                                            <span class="badge badge-secondary">Se genera al descargar</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex" style="gap:8px; flex-wrap:wrap;">
                                        <a class="btn btn-success"
                                           href="{{ route('daily_reports.descargar', ['tipo' => $tipo, 'fecha' => $fecha, 'turno_id' => $turno_id]) }}">
                                            <i class="fa-solid fa-download"></i> Descargar
                                        </a>

                                        @can('crear reportes')
                                            <form action="{{ route('daily_reports.generar') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="fecha" value="{{ $fecha }}">
                                                <input type="hidden" name="turno_id" value="{{ $turno_id }}">
                                                <input type="hidden" name="tipos[]" value="{{ $tipo }}">
                                                <button class="btn btn-outline-primary" type="submit">
                                                    <i class="fa-solid fa-gear"></i> Generar
                                                </button>
                                            </form>
                                        @endcan

                                        @if($exists)
                                            <button type="button" class="btn btn-outline-dark" disabled>
                                                {{ $estado[$tipo]['name'] ?? '' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@stop

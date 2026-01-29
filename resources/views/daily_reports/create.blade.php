@extends('adminlte::page')

@section('title', 'Generar Reporte Diario')

@section('content_header')
    <h1>Generar Reporte Diario</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Parámetros</h3>
            </div>

            <div class="card-body">

                @if(session('error'))
                    <div class="alert alert-danger mb-2">{{ session('error') }}</div>
                @endif

                <div class="alert alert-info">
                    <strong>Hora actual:</strong> {{ $now->format('d/m/Y H:i') }} ·
                    <strong>Fecha operativa:</strong> {{ \Carbon\Carbon::parse($fecha_operativa)->format('d/m/Y') }} ·
                    <strong>Turno detectado en servicio:</strong> {{ $turno_en_servicio_id ?? 'No detectado' }}
                </div>

                <form action="{{ route('daily_reports.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha">Fecha (operativa)</label>
                                <input type="date"
                                       name="fecha"
                                       id="fecha"
                                       value="{{ old('fecha', $fecha_operativa) }}"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       required>
                                @error('fecha')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="turno_id">Turno</label>
                                <select name="turno_id"
                                        id="turno_id"
                                        class="form-control @error('turno_id') is-invalid @enderror"
                                        required>
                                    <option value="" disabled>Seleccione...</option>
                                    @foreach($turnos as $t)
                                        <option value="{{ $t->id }}"
                                            {{ (string)old('turno_id', $turno_en_servicio_id) === (string)$t->id ? 'selected' : '' }}>
                                            {{ $t->nombre ?? ('Turno #' . $t->id) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('turno_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_reporte">Tipo de reporte</label>
                                <input type="text"
                                       name="tipo_reporte"
                                       id="tipo_reporte"
                                       value="{{ old('tipo_reporte', 'ESTADO_FUERZA') }}"
                                       class="form-control @error('tipo_reporte') is-invalid @enderror"
                                       required>
                                @error('tipo_reporte')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="notas">Notas</label>
                                <textarea name="notas"
                                          id="notas"
                                          rows="2"
                                          class="form-control @error('notas') is-invalid @enderror"
                                          placeholder="Opcional...">{{ old('notas') }}</textarea>
                                @error('notas')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-2">Preview del estado de fuerza (turno detectado)</h5>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $estado_fuerza['totales']['laborando'] }}</h3>
                                            <p>Laborando</p>
                                        </div>
                                        <div class="icon"><i class="fas fa-user-check"></i></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $estado_fuerza['totales']['descanso'] }}</h3>
                                            <p>Descanso</p>
                                        </div>
                                        <div class="icon"><i class="fas fa-user-clock"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Dependencia</th>
                                            <th class="text-center">Laborando</th>
                                            <th class="text-center">Descanso</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estado_fuerza['por_dependencia'] as $dep => $t)
                                            <tr>
                                                <td>{{ $dep ?? 'Sin dependencia' }}</td>
                                                <td class="text-center">{{ $t['laborando'] }}</td>
                                                <td class="text-center">{{ $t['descanso'] }}</td>
                                                <td class="text-center">{{ $t['total'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-file-circle-plus"></i> Generar reporte
                        </button>

                        <a href="{{ route('daily_reports.index') }}" class="btn btn-secondary">
                            Volver
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@stop

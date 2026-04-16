@extends('adminlte::page')

@section('title', 'Pase de Lista Canina')

@section('content_header')
    <h1>Pase de Lista Canina</h1>
@stop

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Reportes generados</h3>
    </div>

    <div class="card-body">

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" value="{{ $fecha_desde }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ $fecha_hasta }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Turno</label>
                    <input type="number" name="turno_id" value="{{ $turno_id }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Turno</th>
                    <th>Archivo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportes as $r)
                    <tr>
                        <td>{{ $r->fecha }}</td>
                        <td>{{ $r->turno_id }}</td>
                        <td>{{ $r->archivo ?? '—' }}</td>
                        <td>
                            <a href="{{ route('daily_reports.descargar', [$r->id, 'pase_lista_canina']) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Sin registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $reportes->links() }}

    </div>
</div>
@stop

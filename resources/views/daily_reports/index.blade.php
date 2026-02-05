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
                <h3 class="card-title">Historial</h3>

                <div class="card-tools d-flex" style="gap:8px;">
                    @can('crear reportes')
                        <form action="{{ route('daily_reports.generar') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-file-circle-plus"></i> Generar hoy
                            </button>
                        </form>
                    @endcan

                    <a href="{{ route('daily_reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate"></i> Refrescar
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-2">{{ session('error') }}</div>
                @endif

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th><center>#</center></th>
                            <th><center>Fecha</center></th>
                            <th><center>Tipo</center></th>
                            <th><center>Turno</center></th>
                            <th><center>Generado por</center></th>
                            <th style="width:280px;"><center>Descarga Armamento</center></th>
                            <th style="width:190px;"><center>Lista de Personal</center></th>
                            <th style="width:90px;"><center>Ver</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $r)
                            @php
                                $deps = $r->rows()
                                    ->select('dependencia')
                                    ->whereNotNull('dependencia')
                                    ->where('dependencia','!=','')
                                    ->where(function($q){
                                        $q->whereNotNull('arma_corta')
                                          ->orWhereNotNull('arma_larga');
                                    })
                                    ->distinct()
                                    ->orderBy('dependencia')
                                    ->pluck('dependencia');

                                $dep_default = $deps->first();
                                $armamento_url_base = route('daily_reports.descargar', ['daily_report' => $r->id, 'tipo' => 'excel_armamento']);
                            @endphp

                            <tr>
                                <td><center>{{ $r->id }}</center></td>
                                <td><center>{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</center></td>
                                <td><center>{{ $r->tipo_reporte }}</center></td>
                                <td><center>{{ $r->turno?->nombre ?? ('Turno #' . $r->turno_id) }}</center></td>
                                <td><center>{{ $r->generadoPor?->name ?? ('User #' . $r->generado_por) }}</center></td>

                                <td>
                                    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
                                        <select class="form-control form-control-sm js-dep" style="max-width: 220px;" {{ $deps->isEmpty() ? 'disabled' : '' }}>
                                            @forelse($deps as $d)
                                                <option value="{{ $d }}" {{ $d === $dep_default ? 'selected' : '' }}>
                                                    {{ $d }}
                                                </option>
                                            @empty
                                                <option value="">Sin armamento</option>
                                            @endforelse
                                        </select>

                                        <a
                                            href="{{ $deps->isEmpty() ? '#' : ($armamento_url_base . '?dependencia=' . urlencode($dep_default)) }}"
                                            data-base="{{ $armamento_url_base }}"
                                            class="btn btn-success btn-sm js-btn-excel {{ $deps->isEmpty() ? 'disabled' : '' }}"
                                            title="Descargar Excel Armamento"
                                        >
                                            <i class="fa-solid fa-file-excel"></i>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <center>
                                        <a
                                            href="{{ route('daily_reports.descargar', ['daily_report' => $r->id, 'tipo' => 'excel_lista_personal']) }}"
                                            class="btn btn-success btn-sm"
                                            title="Descargar Lista de Personal"
                                        >
                                            <i class="fa-solid fa-file-excel"></i>
                                            Lista
                                        </a>
                                    </center>
                                </td>

                                <td>
                                    <center>
                                        <a href="{{ route('daily_reports.show', $r->id) }}" class="btn btn-info btn-sm" title="Ver reporte">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"><center>Sin reportes todavía.</center></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr').forEach(function (tr) {
        const sel = tr.querySelector('.js-dep');
        const btn = tr.querySelector('.js-btn-excel');
        if (!sel || !btn) return;

        const base = btn.getAttribute('data-base');
        if (!base) return;

        const setHref = function () {
            const dep = sel.value || '';
            btn.setAttribute('href', base + '?dependencia=' + encodeURIComponent(dep));
        };

        sel.addEventListener('change', setHref);
        if (btn.getAttribute('href') !== '#') setHref();
    });
});
</script>
@stop

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
                            <th style="width:90px;"><center>Ver</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $r)
                            @php
                                // Si NO traes rows en index, esto hará N+1.
                                // Ideal: en el controller index() agrega withCount o un eager load básico.
                                // Aquí lo dejamos simple, pero funcional.
                                $deps = $r->rows()
                                    ->select('dependencia')
                                    ->whereNotNull('dependencia')
                                    ->where('dependencia','!=','')
                                    ->distinct()
                                    ->orderBy('dependencia')
                                    ->pluck('dependencia');

                                $dep_default = $deps->first();
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
                                                <option value="">Sin dependencia</option>
                                            @endforelse
                                        </select>

                                        <a
                                            href="{{ $deps->isEmpty()
                                                ? '#'
                                                : route('daily_reports.descargar', ['daily_report' => $r->id, 'tipo' => 'excel_armamento']) . '?dependencia=' . urlencode($dep_default)
                                            }}"
                                            class="btn btn-success btn-sm js-btn-excel {{ $deps->isEmpty() ? 'disabled' : '' }}"
                                            title="Descargar Excel Armamento"
                                        >
                                            <i class="fa-solid fa-file-excel"></i>
                                        </a>
                                    </div>
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
                                <td colspan="7"><center>Sin reportes todavía.</center></td>
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

        const baseHref = btn.getAttribute('href');
        if (!baseHref || baseHref === '#') return;

        sel.addEventListener('change', function () {
            const dep = sel.value || '';
            const clean = baseHref.split('?')[0];
            btn.setAttribute('href', clean + '?dependencia=' + encodeURIComponent(dep));
        });
    });
});
</script>
@stop

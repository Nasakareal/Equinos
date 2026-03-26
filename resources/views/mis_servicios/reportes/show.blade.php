@extends('adminlte::page')

@section('title', 'Detalle del Reporte')

@section('content_header')
    <h1>Detalle del Reporte Operativo</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-success">

            <div class="card-header">
                <h3 class="card-title">
                    {{ $reporte->tipo_reporte ?? 'REPORTE' }}
                    @if($reporte->fecha)
                        · {{ optional($reporte->fecha)->format('d/m/Y') }}
                    @endif
                    @if($reporte->hora)
                        · {{ \Carbon\Carbon::parse($reporte->hora)->format('H:i') }}
                    @endif
                </h3>

                <div class="card-tools">
                    <a href="{{ route('mis_servicios.show', $servicio->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver al servicio
                    </a>

                    <a href="{{ route('mis_servicios.reportes.index', $servicio->id) }}" class="btn btn-info btn-sm">
                        <i class="fa-solid fa-list"></i> Ver reportes
                    </a>

                    @can('editar reportes de servicios')
                        <a href="{{ route('mis_servicios.reportes.edit', [$servicio->id, $reporte->id]) }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                    @can('compartir whatsapp reportes de servicios')
                        <a href="{{ route('mis_servicios.reportes.whatsapp', [$servicio->id, $reporte->id]) }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>

                        <a href="{{ route('mis_servicios.reportes.compartir_nativo', [$servicio->id, $reporte->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-share-nodes"></i> Compartir nativo
                        </a>
                    @endcan

                    @can('eliminar reportes de servicios')
                        <form action="{{ route('mis_servicios.reportes.destroy', [$servicio->id, $reporte->id]) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                <i class="fa-regular fa-trash-can"></i> Eliminar
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                <ul class="nav nav-tabs" id="reporteTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-resumen-tab" data-toggle="tab" href="#tab-resumen" role="tab">
                            <i class="fa-solid fa-file-lines"></i> Resumen
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-servicio-tab" data-toggle="tab" href="#tab-servicio" role="tab">
                            <i class="fa-solid fa-briefcase"></i> Servicio base
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-whatsapp-tab" data-toggle="tab" href="#tab-whatsapp" role="tab">
                            <i class="fa-brands fa-whatsapp"></i> Texto WhatsApp
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-fotos-tab" data-toggle="tab" href="#tab-fotos" role="tab">
                            <i class="fa-solid fa-camera"></i> Evidencia fotográfica
                        </a>
                    </li>
                </ul>

                <div class="tab-content pt-3">

                    {{-- TAB RESUMEN --}}
                    <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fa-solid fa-file-signature"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tipo de reporte</span>
                                        <span class="info-box-number">{{ $reporte->tipo_reporte ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box bg-gradient-info">
                                    <span class="info-box-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fecha</span>
                                        <span class="info-box-number">
                                            {{ $reporte->fecha ? optional($reporte->fecha)->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box bg-gradient-primary">
                                    <span class="info-box-icon"><i class="fa-solid fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Hora</span>
                                        <span class="info-box-number">
                                            {{ $reporte->hora ? \Carbon\Carbon::parse($reporte->hora)->format('H:i') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box bg-gradient-dark">
                                    <span class="info-box-icon"><i class="fa-solid fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Capturó</span>
                                        <span class="info-box-number">{{ $reporte->creador->name ?? $reporte->creador->nombre ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Municipio</th>
                                            <td>{{ $reporte->municipio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lugar</th>
                                            <td>{{ $reporte->lugar ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Asunto</th>
                                            <td>{{ $reporte->asunto ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Latitud</th>
                                            <td>{{ $reporte->lat ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Longitud</th>
                                            <td>{{ $reporte->lng ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ubicación</th>
                                            <td>
                                                @if(!is_null($reporte->lat) && !is_null($reporte->lng))
                                                    <a href="https://www.google.com/maps?q={{ $reporte->lat }},{{ $reporte->lng }}" target="_blank" rel="noopener">
                                                        Ver en mapa
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Creado</th>
                                            <td>{{ $reporte->created_at ? $reporte->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Actualizado</th>
                                            <td>{{ $reporte->updated_at ? $reporte->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Servicio relacionado</th>
                                            <td>#{{ $servicio->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipo de servicio</th>
                                            <td>{{ $servicio->tipo_servicio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unidad clave</th>
                                            <td>{{ $servicio->unidad_clave ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total de fotos</th>
                                            <td>{{ $reporte->fotos->count() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa-solid fa-align-left"></i> Narrativa</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($reporte->narrativa ?? 'Sin narrativa registrada.')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="card card-outline card-info h-100">
                                            <div class="card-header">
                                                <h3 class="card-title">Estado de fuerza</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->estado_fuerza_texto ?? 'Sin información registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card card-outline card-warning h-100">
                                            <div class="card-header">
                                                <h3 class="card-title">Acciones a realizar</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->acciones_a_realizar ?? 'Sin información registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card card-outline card-primary h-100">
                                            <div class="card-header">
                                                <h3 class="card-title">Acciones realizadas</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->acciones_realizadas ?? 'Sin información registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card card-outline card-success h-100">
                                            <div class="card-header">
                                                <h3 class="card-title">Resultados</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->resultados ?? 'Sin información registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card card-outline card-danger">
                                            <div class="card-header">
                                                <h3 class="card-title">Datos de la persona asegurada</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->datos_persona_asegurada ?? 'Sin información registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card card-outline card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">Conclusión / cierre</h3>
                                            </div>
                                            <div class="card-body">
                                                {!! nl2br(e($reporte->conclusion ?? 'Sin conclusión registrada.')) !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB SERVICIO --}}
                    <div class="tab-pane fade" id="tab-servicio" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Fecha del servicio</th>
                                            <td>{{ $servicio->fecha ? optional($servicio->fecha)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hora</th>
                                            <td>{{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hora fin</th>
                                            <td>{{ $servicio->hora_fin ? \Carbon\Carbon::parse($servicio->hora_fin)->format('H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Categoría</th>
                                            <td>{{ $servicio->categoria_registro ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipo de servicio</th>
                                            <td>{{ $servicio->tipo_servicio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estatus del servicio</th>
                                            <td>{{ $servicio->estatus_servicio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unidad clave</th>
                                            <td>{{ $servicio->unidad_clave ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>CRP</th>
                                            <td>{{ $servicio->crp ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Municipio</th>
                                            <td>{{ $servicio->municipio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lugar</th>
                                            <td>{{ $servicio->lugar ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Asunto</th>
                                            <td>{{ $servicio->asunto ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Objetivo</th>
                                            <td>{{ $servicio->objetivo_servicio ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Personal</th>
                                            <td>{{ $servicio->personal->nombres ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Canino</th>
                                            <td>{{ $servicio->canino->nombre ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Equino</th>
                                            <td>{{ $servicio->equino->nombre ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Patrulla</th>
                                            <td>{{ $servicio->patrulla->numero_economico ?? $servicio->patrulla->placas ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Descripción del servicio</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($servicio->descripcion ?? 'Sin descripción registrada.')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-outline card-info h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Acciones realizadas del servicio</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($servicio->acciones_realizadas ?? 'Sin información registrada.')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-outline card-success h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Resultados del servicio</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($servicio->resultados ?? 'Sin información registrada.')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">Conclusión operativa</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($servicio->conclusion_operativa ?? 'Sin conclusión registrada.')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB WHATSAPP --}}
                    <div class="tab-pane fade" id="tab-whatsapp" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                @can('compartir whatsapp reportes de servicios')
                                    <a href="{{ route('mis_servicios.reportes.whatsapp', [$servicio->id, $reporte->id]) }}" target="_blank" class="btn btn-success">
                                        <i class="fa-brands fa-whatsapp"></i> Compartir por WhatsApp
                                    </a>

                                    <a href="{{ route('mis_servicios.reportes.compartir_nativo', [$servicio->id, $reporte->id]) }}" class="btn btn-primary">
                                        <i class="fa-solid fa-share-nodes"></i> Compartir nativo
                                    </a>
                                @endcan
                            </div>

                            <div class="col-md-12">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Vista previa del texto</h3>
                                    </div>
                                    <div class="card-body">
                                        <pre style="white-space: pre-wrap; word-break: break-word; margin-bottom: 0;">{{ $reporte->whatsapp_texto ?? 'Sin texto generado.' }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB FOTOS --}}
                    <div class="tab-pane fade" id="tab-fotos" role="tabpanel">
                        <div class="row">
                            @forelse($reporte->fotos as $foto)
                                <div class="col-md-4 mb-4">
                                    <div class="card card-outline card-primary h-100">
                                        <div class="card-body text-center">
                                            @php
                                                $fotoUrl = !empty($foto->ruta) ? Storage::url($foto->ruta) : null;
                                            @endphp

                                            @if($fotoUrl)
                                                <a href="{{ $fotoUrl }}" target="_blank" rel="noopener">
                                                    <img
                                                        src="{{ $fotoUrl }}"
                                                        alt="Foto del reporte"
                                                        class="img-fluid rounded shadow"
                                                        style="width: 100%; height: 260px; object-fit: cover;"
                                                    >
                                                </a>
                                            @else
                                                <div class="text-muted py-5">
                                                    <i class="fa-regular fa-image fa-2x mb-2"></i>
                                                    <div>Imagen no disponible</div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="card-footer">
                                            <div><strong>Descripción:</strong> {{ $foto->descripcion ?? 'Sin descripción' }}</div>
                                            <div class="text-muted small mt-1">
                                                {{ $foto->created_at ? $foto->created_at->format('d/m/Y H:i') : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <div class="alert alert-secondary mb-0">
                                        Este reporte no tiene evidencia fotográfica registrada.
                                    </div>
                                </div>
                            @endforelse
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
input.form-control,
textarea.form-control,
select.form-control {
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    background-color: #25364a !important;
    color: #ffffff !important;
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

pre {
    background-color: #1f2d3d;
    color: #ffffff;
    border: 1px solid #3c4b64;
    border-radius: 6px;
    padding: 15px;
}
</style>
@stop

@section('js')
<script>
@if(session('success'))
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 3000
});
@endif

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');

    Swal.fire({
        title: '¿Eliminar reporte?',
        text: 'Esta acción no se puede revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@stop

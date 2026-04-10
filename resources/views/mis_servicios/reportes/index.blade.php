@extends('adminlte::page')

@section('title', 'Reportes del Servicio')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Reportes del Servicio</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Listado de reportes operativos capturados para este servicio
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="{{ route('mis_servicios.show', $servicio->id) }}" class="btn btn-secondary shadow-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver al servicio
            </a>

            <a href="{{ route('mis_servicios.reportes.create', $servicio->id) }}" class="btn btn-primary shadow-sm">
                <i class="fa-solid fa-plus mr-1"></i> Nuevo reporte
            </a>
        </div>
    </div>
@stop

@section('content')
    @php
        $tipoServicioClass = match (strtoupper((string) $servicio->tipo_servicio)) {
            'SEGURIDAD' => 'badge badge-info',
            'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
            'BUSQUEDA' => 'badge badge-danger',
            'DESFILES' => 'badge badge-purple',
            'PROXIMIDAD SOCIAL' => 'badge badge-success',
            'ACTOS CIVICOS' => 'badge badge-warning',
            'OTRO' => 'badge badge-secondary',
            default => 'badge badge-secondary',
        };
    @endphp

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Datos base del servicio</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">ID servicio</div>
                        <div class="info-value">#{{ $servicio->id }}</div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Fecha</div>
                        <div class="info-value">
                            {{ $servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Hora</div>
                        <div class="info-value">
                            {{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de servicio</div>
                        <div class="info-value">
                            <span class="{{ $tipoServicioClass }} badge-pill px-3 py-2">
                                {{ $servicio->tipo_servicio ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-card">
                        <div class="info-label">Municipio</div>
                        <div class="info-value">{{ $servicio->municipio ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-8 mb-3">
                    <div class="info-card">
                        <div class="info-label">Lugar base</div>
                        <div class="info-value">{{ $servicio->lugar ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="info-card">
                        <div class="info-label">Asunto base</div>
                        <div class="info-value">{{ $servicio->asunto ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Reportes capturados</h3>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($reportes->count())
                <div class="table-responsive">
                    <table id="tablaReportesServicio" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo de reporte</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Municipio</th>
                                <th>Asunto</th>
                                <th>Lugar</th>
                                <th>Fotos</th>
                                <th style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes as $reporte)
                                <tr>
                                    <td>#{{ $reporte->id }}</td>
                                    <td>
                                        <span class="badge badge-secondary px-3 py-2">
                                            {{ $reporte->tipo_reporte ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $reporte->fecha ? \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $reporte->hora ? \Carbon\Carbon::parse($reporte->hora)->format('H:i') : '-' }}
                                    </td>
                                    <td>{{ $reporte->municipio ?? '-' }}</td>
                                    <td>{{ $reporte->asunto ?? '-' }}</td>
                                    <td>{{ $reporte->lugar ?? '-' }}</td>
                                    <td>{{ $reporte->fotos_count ?? ($reporte->fotos->count() ?? 0) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mis_servicios.reportes.show', [$servicio->id, $reporte->id]) }}" class="btn btn-info btn-sm">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="{{ route('mis_servicios.reportes.edit', [$servicio->id, $reporte->id]) }}" class="btn btn-success btn-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>

                                            @if(Route::has('mis_servicios.reportes.destroy'))
                                                <form action="{{ route('mis_servicios.reportes.destroy', [$servicio->id, $reporte->id]) }}" method="POST" class="formEliminarReporte d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-light border text-center mb-0">
                    <i class="fa-regular fa-folder-open mr-1"></i>
                    Aún no hay reportes capturados para este servicio.
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
<script>
    $(function () {
        $('#tablaReportesServicio').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-MX.json'
            },
            order: [[0, 'desc']]
        });

        $(document).on('submit', '.formEliminarReporte', function (e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: '¿Eliminar este reporte?',
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
    });
</script>
@stop

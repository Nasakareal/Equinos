@extends('adminlte::page')

@section('title', 'Puesta a Disposición')

@section('content_header')
    <h1>Puesta a Disposición</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">
                    Folio: <span class="badge badge-danger" style="font-size: 1.05rem;">{{ $pd->folio }}</span>
                </h3>

                <div class="card-tools">

                    <a href="{{ route('puestas_disposicion.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    @can('editar puestas_disposicion')
                        <a href="{{ route('puestas_disposicion.edit', $pd) }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                </div>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="info-box bg-gradient-purple">
                            <span class="info-box-icon"><i class="fa-solid fa-hashtag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Folio</span>
                                <span class="info-box-number">{{ $pd->folio }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon"><i class="fa-solid fa-user-shield"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Personal</span>
                                <span class="info-box-number">
                                    {{ trim(($pd->personal->grado ?? '').' '.($pd->personal->nombres ?? '')) ?: '-' }}
                                </span>
                                <span class="info-box-text">
                                    {{ $pd->personal->cargo ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fa-solid fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Año</span>
                                <span class="info-box-number">{{ $pd->anio }}</span>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-bordered table-sm">
                            <tbody>

                                <tr>
                                    <th style="width: 35%">ID</th>
                                    <td>{{ $pd->id }}</td>
                                </tr>

                                <tr>
                                    <th>Folio num</th>
                                    <td>{{ $pd->folio_num }}</td>
                                </tr>

                                <tr>
                                    <th>Registrado</th>
                                    <td>{{ $pd->created_at ? $pd->created_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>

                                <tr>
                                    <th>Actualizado</th>
                                    <td>{{ $pd->updated_at ? $pd->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>


                    <div class="col-md-6">

                        <table class="table table-bordered table-sm">
                            <tbody>

                                <tr>
                                    <th style="width: 35%">Observaciones</th>
                                    <td style="text-align:left;">{{ $pd->observaciones ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <th>PDF</th>
                                    <td>

                                        @if(!empty($pd->archivo_pdf))
                                            <a href="{{ Storage::disk('public')->url($pd->archivo_pdf) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                            </a>
                                        @else
                                            <span class="text-muted">Sin archivo</span>
                                        @endif

                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@stop


@section('css')
<style>

.table th, .table td{
    text-align:center;
    vertical-align:middle;
}

</style>
@stop


@section('js')

@if(session('success'))
<script>
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 3000
});
</script>
@endif

@stop

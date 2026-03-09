@extends('adminlte::page')

@section('title', 'Respaldos SQL')

@section('content_header')
    <h1>Respaldos SQL</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Archivos de respaldo disponibles</h3>

                <div class="card-tools">
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if($files->isEmpty())
                    <div class="alert alert-info mb-0">
                        No hay respaldos SQL disponibles en <strong>storage/app/backups_sql</strong>.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Última modificación</th>
                                    <th style="width: 180px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td>{{ $file['name'] }}</td>
                                        <td>{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($file['last_modified'])->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('backups_sql.download', $file['name']) }}" class="btn btn-success btn-sm">
                                                <i class="fa-solid fa-download"></i> Descargar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

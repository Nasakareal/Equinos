@extends('adminlte::page')

@section('title', 'Detalle del Servicio')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Detalle del Servicio / Apoyo / Memorándum</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Información completa del registro operativo
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="{{ url('/servicios') }}" class="btn btn-secondary shadow-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver
            </a>

            @can('editar servicios')
                <a href="{{ url('/servicios/' . $servicio->id . '/edit') }}" class="btn btn-success shadow-sm">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Editar
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    @php
        $tipoRegistroClass = match (strtoupper((string) $servicio->tipo_registro)) {
            'SERVICIO' => 'badge badge-primary',
            'APOYO' => 'badge badge-success',
            'MEMORANDUM' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };

        $tipoServicioClass = match (strtoupper((string) $servicio->tipo_servicio)) {
            'SEGURIDAD' => 'badge badge-info',
            'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
            'BUSQUEDA' => 'badge badge-danger',
            'DESFILES' => 'badge badge-purple',
            'PROXIMIDAD SOCIAL' => 'badge badge-success',
            'ACTOS CIVICOS' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };

        $patrullaTexto = '-';
        if (!empty($servicio->patrulla)) {
            $patrullaTexto =
                $servicio->patrulla->nombre ??
                $servicio->patrulla->numero ??
                $servicio->patrulla->placas ??
                ('ID ' . $servicio->patrulla->id);
        }
    @endphp

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>#{{ $servicio->id }}</h3>
                    <p>ID del registro</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box {{ $servicio->cumplio ? 'bg-success' : 'bg-danger' }} shadow-sm">
                <div class="inner">
                    <h3>{{ $servicio->cumplio ? 'Sí' : 'No' }}</h3>
                    <p>Cumplimiento</p>
                </div>
                <div class="icon">
                    <i class="fa-solid {{ $servicio->cumplio ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-indigo shadow-sm">
                <div class="inner">
                    <h3>{{ $servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') : '-' }}</h3>
                    <p>Fecha</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-secondary shadow-sm">
                <div class="inner">
                    <h3>{{ $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-' }}</h3>
                    <p>Hora</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Información general</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de registro</div>
                        <div class="info-value">
                            <span class="{{ $tipoRegistroClass }} badge-pill px-3 py-2">
                                {{ $servicio->tipo_registro ?? '-' }}
                            </span>
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

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de búsqueda</div>
                        <div class="info-value">
                            {{ $servicio->tipo_busqueda ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Creado por</div>
                        <div class="info-value">
                            {{ $servicio->user->name ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-outline card-info shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Asignación operativa</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Elemento</div>
                        <div class="info-value">
                            {{ $servicio->personal->nombres ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Canino</div>
                        <div class="info-value">
                            {{ $servicio->canino->nombre ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Equino</div>
                        <div class="info-value">
                            {{ $servicio->equino->nombre ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Patrulla</div>
                        <div class="info-value">
                            {{ $patrullaTexto }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Indicadores del servicio</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="indicator-box {{ $servicio->seguridad ? 'active-box' : '' }}">
                        <div class="indicator-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div class="indicator-title">Seguridad</div>
                        <div class="indicator-status">
                            {{ $servicio->seguridad ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="indicator-box {{ $servicio->barridos_seguridad ? 'active-box' : '' }}">
                        <div class="indicator-icon">
                            <i class="fa-solid fa-radar"></i>
                        </div>
                        <div class="indicator-title">Barridos</div>
                        <div class="indicator-status">
                            {{ $servicio->barridos_seguridad ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="indicator-box {{ $servicio->desfiles ? 'active-box' : '' }}">
                        <div class="indicator-icon">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <div class="indicator-title">Desfiles</div>
                        <div class="indicator-status">
                            {{ $servicio->desfiles ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 mb-3">
                    <div class="indicator-box {{ $servicio->proximidad_social ? 'active-box' : '' }}">
                        <div class="indicator-icon">
                            <i class="fa-solid fa-handshake-angle"></i>
                        </div>
                        <div class="indicator-title">Proximidad social</div>
                        <div class="indicator-status">
                            {{ $servicio->proximidad_social ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="indicator-box {{ $servicio->actos_civicos ? 'active-box' : '' }}">
                        <div class="indicator-icon">
                            <i class="fa-solid fa-landmark"></i>
                        </div>
                        <div class="indicator-title">Actos cívicos</div>
                        <div class="indicator-status">
                            {{ $servicio->actos_civicos ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-outline card-dark shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Observaciones</h3>
        </div>

        <div class="card-body">
            <div class="observaciones-box">
                {{ $servicio->observaciones ?: 'Sin observaciones registradas.' }}
            </div>
        </div>
    </div>

    @can('eliminar servicios')
        <div class="text-right mb-3">
            <form action="{{ url('/servicios/' . $servicio->id) }}" method="POST" class="d-inline-block" id="deleteFormServicio">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger shadow-sm" id="btnEliminarServicio">
                    <i class="fa-regular fa-trash-can mr-1"></i> Eliminar registro
                </button>
            </form>
        </div>
    @endcan
@stop

@section('css')
<style>
    .card,
    .small-box,
    .info-card,
    .indicator-box,
    .observaciones-box {
        border-radius: 14px;
    }

    .small-box {
        overflow: hidden;
    }

    .small-box .icon {
        top: 10px;
        right: 12px;
        font-size: 52px;
        opacity: .18;
    }

    .bg-indigo {
        background-color: #6610f2 !important;
        color: #fff !important;
    }

    .badge-purple {
        background-color: #6f42c1;
        color: #fff;
    }

    .badge-pill {
        font-size: .84rem;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .info-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 16px;
        height: 100%;
    }

    .info-label {
        font-size: .85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 8px;
        letter-spacing: .4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #212529;
        word-break: break-word;
    }

    .indicator-box {
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        padding: 18px 12px;
        text-align: center;
        height: 100%;
        transition: all .2s ease;
    }

    .indicator-box.active-box {
        background: linear-gradient(135deg, rgba(40,167,69,.12), rgba(0,123,255,.08));
        border-color: rgba(40,167,69,.35);
        box-shadow: 0 8px 20px rgba(0,0,0,.06);
    }

    .indicator-icon {
        font-size: 1.6rem;
        margin-bottom: 10px;
        color: #495057;
    }

    .indicator-title {
        font-weight: 700;
        font-size: .92rem;
        margin-bottom: 6px;
    }

    .indicator-status {
        font-size: .95rem;
        color: #6c757d;
        font-weight: 600;
    }

    .observaciones-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 18px;
        min-height: 120px;
        white-space: pre-line;
        font-size: 1rem;
        line-height: 1.6;
    }

    .btn {
        border-radius: 10px;
    }
</style>
@stop

@section('js')
<script>
    @if (session('success'))
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 12000
        });
    @endif

    $(document).on('click', '#btnEliminarServicio', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro de eliminar este registro?',
            text: '¡No podrás revertir esta acción!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteFormServicio').submit();
            }
        });
    });
</script>
@stop

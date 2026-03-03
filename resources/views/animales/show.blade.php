@extends('adminlte::page')

@section('title', 'Expediente del Animal')

@section('content_header')
    <h1>Expediente: {{ $animal->nombre }}</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">
                    {{ $animal->tipo == 'EQUINO' ? 'Equino' : 'Canino' }} · {{ $animal->nombre }}
                </h3>

                <div class="card-tools">

                    @can('ver animales')
                        <a href="{{ url('/animales') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    @endcan

                    @can('editar animales')
                        <a href="{{ url('/animales/'.$animal->id.'/edit') }}" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    @endcan

                </div>
            </div>

            <div class="card-body">

                <ul class="nav nav-tabs" id="animalTabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="tab-datos-tab" data-toggle="tab" href="#tab-datos" role="tab">
                            <i class="fa-solid fa-id-card"></i> Datos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-medico-tab" data-toggle="tab" href="#tab-medico" role="tab">
                            <i class="fa-solid fa-notes-medical"></i> Historial Médico
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-incidencias-tab" data-toggle="tab" href="#tab-incidencias" role="tab">
                            <i class="fa-solid fa-triangle-exclamation"></i> Incidencias
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-asignaciones-tab" data-toggle="tab" href="#tab-asignaciones" role="tab">
                            <i class="fa-solid fa-user-shield"></i> Asignaciones
                        </a>
                    </li>

                </ul>

                <div class="tab-content pt-3">

                    <div class="tab-pane fade show active" id="tab-datos" role="tabpanel">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-purple">
                                    <span class="info-box-icon"><i class="fa-solid fa-paw"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nombre</span>
                                        <span class="info-box-number">{{ $animal->nombre }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-info">
                                    <span class="info-box-icon"><i class="fa-solid fa-dna"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Raza</span>
                                        <span class="info-box-number">{{ $animal->raza ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fa-solid fa-shield-heart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Estatus</span>
                                        <span class="info-box-number">{{ $animal->estatus }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Tipo</th>
                                            <td>{{ $animal->tipo }}</td>
                                        </tr>
                                        <tr>
                                            <th>Procedencia</th>
                                            <td>{{ $animal->procedencia ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sexo</th>
                                            <td>{{ $animal->sexo ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Color</th>
                                            <td>{{ $animal->color ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Especialidad</th>
                                            <td>{{ $animal->especialidad ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Marcaje</th>
                                            <td>{{ $animal->marcaje ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Chip</th>
                                            <td>{{ $animal->chip ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Fecha nacimiento</th>
                                            <td>{{ $animal->fecha_nacimiento ? $animal->fecha_nacimiento->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Edad</th>
                                            <td>{{ $animal->edad_texto ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Forraje (kg/día)</th>
                                            <td>{{ $animal->forraje_kg_diario ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Grano (kg/día)</th>
                                            <td>{{ $animal->grano_kg_diario ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Observaciones</th>
                                            <td>{{ $animal->observaciones ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Características</th>
                                            <td>{{ $animal->caracteristicas ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-medico" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Historial Médico</h5>

                            @can('editar animales')
                                <a href="{{ route('animales.medico.create', $animal->id) }}" class="btn btn-purple btn-sm">
                                    <i class="fa-solid fa-plus"></i> Agregar registro
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Veterinario</th>
                                        <th>Costo</th>
                                        <th>Próxima</th>
                                        <th>Archivos</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse(($animal->medicalRecords ?? []) as $r)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
                                            <td>{{ $r->tipo }}</td>
                                            <td>{{ $r->veterinario ?? '-' }}</td>
                                            <td>{{ $r->costo ?? '-' }}</td>
                                            <td>{{ $r->proxima_cita ? \Carbon\Carbon::parse($r->proxima_cita)->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if(($r->files ?? collect())->count())
                                                    @foreach($r->files as $f)
                                                        <div class="mb-1">
                                                            <a href="{{ Storage::url($f->archivo) }}" target="_blank">
                                                                <i class="fas fa-file"></i> Ver
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Sin archivos</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">

                                                    @can('editar animales')
                                                        <a href="{{ route('animales.medico.edit', [$animal->id, $r->id]) }}" class="btn btn-success btn-sm">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    @endcan

                                                    @can('editar animales')
                                                        <form action="{{ route('animales.medico.destroy', [$animal->id, $r->id]) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Sin registros médicos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-incidencias" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Incidencias</h5>

                            @can('crear incidencias')
                                <a href="{{ route('animales.incidencias.create', $animal->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-plus"></i> Registrar
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Gravedad</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse(($animal->incidences ?? []) as $i)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $i->incidenceType->nombre ?? '-' }}</td>
                                            <td>{{ $i->gravedad }}</td>
                                            <td>{{ $i->descripcion ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group">

                                                    @can('editar incidencias')
                                                        <a href="{{ route('animales.incidencias.edit', [$animal->id, $i->id]) }}" class="btn btn-success btn-sm">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    @endcan

                                                    @can('eliminar incidencias')
                                                        <form action="{{ route('animales.incidencias.destroy', [$animal->id, $i->id]) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Sin incidencias registradas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-asignaciones" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Asignaciones</h5>

                            @can('editar animales')
                                <a href="{{ route('animales.asignaciones.create', $animal->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-plus"></i> Nueva asignación
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Personal</th>
                                        <th>Patrulla</th>
                                        <th>Turno</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse(($animal->assignments ?? []) as $a)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($a->inicio)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $a->fin ? \Carbon\Carbon::parse($a->fin)->format('d/m/Y H:i') : '-' }}</td>
                                            <td>{{ $a->personal->nombres ?? '-' }}</td>
                                            <td>{{ $a->patrol->numero_economico ?? '-' }}</td>
                                            <td>{{ $a->turno->nombre ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @can('editar animales')
                                                        <form action="{{ route('animales.asignaciones.destroy', [$animal->id, $a->id]) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Sin asignaciones</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

::placeholder {
    color: #b8c7ce !important;
    opacity: 1;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

.btn-purple {
    background: linear-gradient(135deg, #6f42c1, #4e2a8e) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 8px 18px;
    box-shadow: 0 4px 10px rgba(111, 66, 193, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-purple:hover {
    background: linear-gradient(135deg, #5a32a3, #3d1f73) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(111, 66, 193, 0.45);
}

.btn-purple:focus,
.btn-purple:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.4) !important;
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
        title: '¿Eliminar registro?',
        text: "Esta acción no se puede revertir",
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

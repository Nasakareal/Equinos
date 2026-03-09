

<?php $__env->startSection('title', 'Expediente del Animal'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Expediente: <?php echo e($animal->nombre); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">
                    <?php echo e($animal->tipo == 'EQUINO' ? 'Equino' : 'Canino'); ?> · <?php echo e($animal->nombre); ?>

                </h3>

                <div class="card-tools">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver animales')): ?>
                        <a href="<?php echo e(url('/animales')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                        <a href="<?php echo e(url('/animales/'.$animal->id.'/edit')); ?>" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    <?php endif; ?>

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

                            
                            <div class="col-md-12 mb-3">
                                <div class="card card-outline card-purple mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fa-solid fa-camera"></i> Foto
                                        </h3>

                                        <div class="card-tools">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                                <a href="<?php echo e(url('/animales/'.$animal->id.'/edit')); ?>" class="btn btn-purple btn-sm">
                                                    <i class="fa-solid fa-upload"></i> Subir / Cambiar
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <?php if(!empty($animal->foto_url)): ?>
                                            <div class="text-center">
                                                <a href="<?php echo e($animal->foto_url); ?>" target="_blank" rel="noopener">
                                                    <img
                                                        src="<?php echo e($animal->foto_url); ?>"
                                                        alt="Foto de <?php echo e($animal->nombre); ?>"
                                                        class="img-fluid rounded shadow"
                                                        style="max-height: 360px; object-fit: cover;"
                                                    >
                                                </a>
                                                <div class="mt-2 text-muted small">
                                                    Click para abrir en grande
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center text-muted py-4">
                                                <i class="fa-regular fa-image fa-2x mb-2"></i>
                                                <div>Sin foto</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-purple">
                                    <span class="info-box-icon"><i class="fa-solid fa-paw"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nombre</span>
                                        <span class="info-box-number"><?php echo e($animal->nombre); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-info">
                                    <span class="info-box-icon"><i class="fa-solid fa-dna"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Raza</span>
                                        <span class="info-box-number"><?php echo e($animal->raza ?? '-'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fa-solid fa-shield-heart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Estatus</span>
                                        <span class="info-box-number"><?php echo e($animal->estatus); ?></span>
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
                                            <td><?php echo e($animal->tipo); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Procedencia</th>
                                            <td><?php echo e($animal->procedencia ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sexo</th>
                                            <td><?php echo e($animal->sexo ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Color</th>
                                            <td><?php echo e($animal->color ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Especialidad</th>
                                            <td><?php echo e($animal->especialidad ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Marcaje</th>
                                            <td><?php echo e($animal->marcaje ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Chip</th>
                                            <td><?php echo e($animal->chip ?? '-'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Fecha nacimiento</th>
                                            <td><?php echo e($animal->fecha_nacimiento ? $animal->fecha_nacimiento->format('d/m/Y') : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Edad</th>
                                            <td><?php echo e($animal->edad_calculada ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Forraje (kg/día)</th>
                                            <td><?php echo e($animal->forraje_kg_diario ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Grano (kg/día)</th>
                                            <td><?php echo e($animal->grano_kg_diario ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Observaciones</th>
                                            <td><?php echo e($animal->observaciones ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Características</th>
                                            <td><?php echo e($animal->caracteristicas ?? '-'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                    
                    <div class="tab-pane fade" id="tab-medico" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Historial Médico</h5>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                <a href="<?php echo e(route('animales.medico.create', $animal->id)); ?>" class="btn btn-purple btn-sm">
                                    <i class="fa-solid fa-plus"></i> Agregar registro
                                </a>
                            <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = ($animal->medicalRecords ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($r->fecha ? \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') : '-'); ?></td>
                                            <td><?php echo e($r->tipo ?? '-'); ?></td>
                                            <td><?php echo e($r->veterinario ?? '-'); ?></td>
                                            <td><?php echo e($r->costo ?? '-'); ?></td>
                                            <td><?php echo e($r->proxima_cita ? \Carbon\Carbon::parse($r->proxima_cita)->format('d/m/Y') : '-'); ?></td>
                                            <td>
                                                <?php if(($r->files ?? collect())->count()): ?>
                                                    <?php $__currentLoopData = $r->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="mb-1">
                                                            <a href="<?php echo e(Storage::url($f->archivo)); ?>" target="_blank" rel="noopener">
                                                                <i class="fas fa-file"></i> Ver
                                                            </a>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin archivos</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                                        <a href="<?php echo e(route('animales.medico.edit', [$animal->id, $r->id])); ?>" class="btn btn-success btn-sm">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                                        <form action="<?php echo e(route('animales.medico.destroy', [$animal->id, $r->id])); ?>" method="POST" style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Sin registros médicos</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    
                    <div class="tab-pane fade" id="tab-incidencias" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Incidencias</h5>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear incidencias')): ?>
                                <a href="<?php echo e(route('animales.incidencias.create', $animal->id)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-plus"></i> Registrar
                                </a>
                            <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = ($animal->incidences ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($i->fecha ? \Carbon\Carbon::parse($i->fecha)->format('d/m/Y H:i') : '-'); ?></td>
                                            <td><?php echo e($i->incidenceType->nombre ?? '-'); ?></td>
                                            <td><?php echo e($i->gravedad ?? '-'); ?></td>
                                            <td><?php echo e($i->descripcion ?? '-'); ?></td>
                                            <td>
                                                <div class="btn-group">

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar incidencias')): ?>
                                                        <a href="<?php echo e(route('animales.incidencias.edit', [$animal->id, $i->id])); ?>" class="btn btn-success btn-sm">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar incidencias')): ?>
                                                        <form action="<?php echo e(route('animales.incidencias.destroy', [$animal->id, $i->id])); ?>" method="POST" style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Sin incidencias registradas</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    
                    <div class="tab-pane fade" id="tab-asignaciones" role="tabpanel">

                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-0">Asignaciones</h5>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                <a href="<?php echo e(route('animales.asignaciones.create', $animal->id)); ?>" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-plus"></i> Nueva asignación
                                </a>
                            <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = ($animal->assignments ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($a->inicio ? \Carbon\Carbon::parse($a->inicio)->format('d/m/Y H:i') : '-'); ?></td>
                                            <td><?php echo e($a->fin ? \Carbon\Carbon::parse($a->fin)->format('d/m/Y H:i') : '-'); ?></td>
                                            <td><?php echo e($a->personal->nombres ?? '-'); ?></td>
                                            <td><?php echo e($a->patrol->numero_economico ?? '-'); ?></td>
                                            <td><?php echo e($a->turno->nombre ?? '-'); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                                        <form action="<?php echo e(route('animales.asignaciones.destroy', [$animal->id, $a->id])); ?>" method="POST" style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Sin asignaciones</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
<?php if(session('success')): ?>
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '<?php echo e(session('success')); ?>',
    showConfirmButton: false,
    timer: 3000
});
<?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/animales/show.blade.php ENDPATH**/ ?>
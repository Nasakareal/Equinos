



<?php $__env->startSection('title', 'Detalle de Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Detalle de Personal</h1>

        <div class="btn-group">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear armamento')): ?>
                <a href="<?php echo e(route('armamento_asignaciones.create', ['personal_id' => $personal->id])); ?>"
                   class="btn btn-primary">
                    <i class="fa-solid fa-gun"></i> Asignar arma
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear incidencias')): ?>
                <a href="<?php echo e(route('incidencias.create', ['personal_id' => $personal->id])); ?>"
                   class="btn btn-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i> Registrar incidencia
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Información General
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            <strong>Nombre completo</strong>
                            <p class="text-muted"><?php echo e($personal->nombres); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Grado</strong>
                            <p class="text-muted"><?php echo e($personal->grado ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Cargo</strong>
                            <p class="text-muted"><?php echo e($personal->cargo ?? '—'); ?></p>
                        </div>

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Usuario del sistema</strong>
                            <p class="text-muted">
                                <?php if($personal->user): ?>
                                    <?php echo e($personal->user->name); ?> <br>
                                    <small><?php echo e($personal->user->email); ?></small>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <strong>No. empleado</strong>
                            <p class="text-muted"><?php echo e($personal->no_empleado ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Dependencia</strong>
                            <p class="text-muted"><?php echo e($personal->dependencia ?? '—'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>CUIP</strong>
                            <p class="text-muted"><?php echo e($personal->cuip ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>CRP</strong>
                            <p class="text-muted"><?php echo e($personal->crp ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Celular</strong>
                            <p class="text-muted"><?php echo e($personal->celular ?? '—'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Área de patrullaje</strong>
                            <p class="text-muted"><?php echo e($personal->area_patrullaje ?? '—'); ?></p>
                        </div>

                        <div class="col-md-3">
                            <strong>Responsable</strong>
                            <p class="text-muted">
                                <?php if($personal->es_responsable): ?>
                                    <span class="badge badge-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">No</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-3">
                            <strong>Estatus</strong>
                            <p class="text-muted">
                                <?php if($personal->activo): ?>
                                    <span class="badge badge-primary">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Observaciones</strong>
                            <p class="text-muted">
                                <?php echo e($personal->observaciones ?: 'Sin observaciones'); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo e(route('personal.index')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    <div class="btn-group">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                            <a href="<?php echo e(route('personal.edit', $personal->id)); ?>" class="btn btn-success">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa-solid fa-gun"></i> Armamento asignado
                    </h3>
                </div>

                <div class="card-body">
                    <?php if(isset($armasActivas) && $armasActivas->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Estado</th>
                                        <th>Fecha asignación</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $armasActivas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($a->weapon->tipo ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->matricula ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->estado ?? 'N/D'); ?></td>
                                            <td><?php echo e(optional($a->fecha_asignacion)->format('d/m/Y H:i') ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->observaciones ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-secondary">Sin arma asignada</span>
                    <?php endif; ?>

                    <?php if(isset($historialArmamento) && $historialArmamento->count()): ?>
                        <hr>
                        <h5 class="mb-3"><i class="fa-solid fa-clock-rotate-left"></i> Historial</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Status</th>
                                        <th>Asignación</th>
                                        <th>Devolución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $historialArmamento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($a->weapon->tipo ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->matricula ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->status ?? 'N/D'); ?></td>
                                            <td><?php echo e(optional($a->fecha_asignacion)->format('d/m/Y H:i') ?? 'N/D'); ?></td>
                                            <td><?php echo e(optional($a->fecha_devolucion)->format('d/m/Y H:i') ?? '---'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        strong { display:block; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/show.blade.php ENDPATH**/ ?>
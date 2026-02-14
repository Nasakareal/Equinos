

<?php $__env->startSection('title', 'Detalle de Unidad'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle de Unidad / Patrulla</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Unidad: <strong><?php echo e($patrol->numero_economico); ?></strong>
                    </h3>

                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                            <a href="<?php echo e(route('patrullas.edit', $patrol->id)); ?>" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('patrullas.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5>Tipo de Unidad</h5>
                            <?php if($patrol->tipo == 'EQUINO'): ?>
                                <span class="badge badge-primary p-2">EQUINO</span>
                            <?php elseif($patrol->tipo == 'CANINO'): ?>
                                <span class="badge badge-warning p-2">CANINO</span>
                            <?php elseif($patrol->tipo == 'RAM'): ?>
                                <span class="badge badge-info p-2">RAM / PATRULLA</span>
                            <?php elseif($patrol->tipo == 'LOGISTICA'): ?>
                                <span class="badge badge-dark p-2">LOGÍSTICA</span>
                            <?php else: ?>
                                <span class="badge badge-secondary p-2"><?php echo e($patrol->tipo); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <h5>Estado</h5>
                            <?php if($patrol->estado == 'ACTIVO'): ?>
                                <span class="badge badge-success p-2">ACTIVO</span>
                            <?php elseif($patrol->estado == 'TALLER'): ?>
                                <span class="badge badge-warning p-2">EN TALLER</span>
                            <?php elseif($patrol->estado == 'BAJA'): ?>
                                <span class="badge badge-danger p-2">BAJA</span>
                            <?php else: ?>
                                <span class="badge badge-secondary p-2"><?php echo e($patrol->estado); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <h5>Observaciones</h5>
                            <p class="text-muted">
                                <?php echo e($patrol->observaciones ?? 'Sin observaciones registradas.'); ?>

                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <strong>Placas:</strong>
                            <p><?php echo e($patrol->placas ?? '-'); ?></p>
                        </div>

                        <div class="col-md-3">
                            <strong>Marca:</strong>
                            <p><?php echo e($patrol->marca ?? '-'); ?></p>
                        </div>

                        <div class="col-md-3">
                            <strong>Modelo:</strong>
                            <p><?php echo e($patrol->modelo ?? '-'); ?></p>
                        </div>

                        <div class="col-md-3">
                            <strong>Año:</strong>
                            <p><?php echo e($patrol->anio ?? '-'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <h4 class="mt-4">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Historial de Asignaciones
                    </h4>

                    <?php if($patrol->assignments && $patrol->assignments->count() > 0): ?>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Turno</th>
                                        <th>Servicio</th>
                                        <th>Encargado</th>
                                        <th>Agregados</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $patrol->assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php
                                            $encargado = $a->personals->firstWhere('pivot.rol', 'ENCARGADO');
                                            $agregados = $a->personals->where('pivot.rol', 'AGREGADO');
                                        ?>

                                        <tr>
                                            <td><?php echo e($a->fecha->format('d/m/Y')); ?></td>
                                            <td><?php echo e($a->turno->nombre ?? '-'); ?></td>
                                            <td><?php echo e($a->servicio ?? '-'); ?></td>

                                            <td>
                                                <?php echo e($encargado ? $encargado->nombres : '-'); ?>

                                            </td>

                                            <td>
                                                <?php if($agregados->count() > 0): ?>
                                                    <ul class="mb-0">
                                                        <?php $__currentLoopData = $agregados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li><?php echo e($p->nombres); ?></li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e($a->personals->count()); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                    <?php else: ?>
                        <p class="text-muted mt-3">
                            Esta unidad aún no tiene asignaciones registradas.
                        </p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        h5 {
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/patrullas/show.blade.php ENDPATH**/ ?>
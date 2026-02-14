

<?php $__env->startSection('title', 'Despliegue Diario'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Asignaciones de Unidades</h1>
    <p class="text-muted mb-0">
        Panel dinámico de despliegue operativo (Equinos y Caninos)
    </p>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row mb-3">
        <div class="col-md-12 text-right">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                <a href="<?php echo e(route('patrullas_asignaciones.create')); ?>">
                    <i class="fa-solid fa-plus"></i> Nueva Asignación
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">

        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php
                $encargado = $a->personals->firstWhere('pivot.rol', 'ENCARGADO');
                $agregados = $a->personals->where('pivot.rol', 'AGREGADO');

                $tipo = $a->patrol->tipo ?? 'RAM';

                $badgeColor = match($tipo) {
                    'EQUINO' => 'primary',
                    'CANINO' => 'warning',
                    'LOGISTICA' => 'dark',
                    default => 'info',
                };
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-<?php echo e($badgeColor); ?> shadow-sm">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-shield-dog"></i>
                            <strong><?php echo e($a->patrol->numero_economico); ?></strong>
                        </h3>

                        <div class="card-tools">
                            <span class="badge badge-<?php echo e($badgeColor); ?>">
                                <?php echo e($tipo); ?>

                            </span>
                        </div>
                    </div>

                    <div class="card-body">

                        <p class="mb-1">
                            <i class="fa-solid fa-calendar-day"></i>
                            <strong><?php echo e($a->fecha->format('d/m/Y')); ?></strong>
                            <span class="text-muted">
                                (<?php echo e($a->turno->nombre ?? 'Sin turno'); ?>)
                            </span>
                        </p>

                        <p class="mb-2">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="text-muted"><?php echo e($a->zona ?? 'Zona no especificada'); ?></span>
                        </p>

                        <hr>

                        <h6 class="mb-1">
                            <i class="fa-solid fa-user-shield"></i>
                            Encargado
                        </h6>

                        <p class="mb-2">
                            <?php echo e($encargado ? $encargado->nombres : 'Sin encargado asignado'); ?>

                        </p>

                        <h6 class="mb-1">
                            <i class="fa-solid fa-users"></i>
                            Agregados (<?php echo e($agregados->count()); ?>)
                        </h6>

                        <?php if($agregados->count() > 0): ?>
                            <ul class="pl-3 mb-2">
                                <?php $__currentLoopData = $agregados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($p->nombres); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-2">Sin agregados</p>
                        <?php endif; ?>

                        <div class="mt-3">
                            <span class="badge badge-secondary p-2">
                                <i class="fa-solid fa-briefcase"></i>
                                <?php echo e($a->servicio ?? 'Servicio general'); ?>

                            </span>
                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a href="<?php echo e(route('patrullas_asignaciones.show', $a->id)); ?>"
                           class="btn btn-sm btn-info">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                            <a href="<?php echo e(route('patrullas_asignaciones.edit', $a->id)); ?>"
                               class="btn btn-sm btn-success">
                                <i class="fa-regular fa-pen-to-square"></i> Editar
                            </a>
                        <?php endif; ?>

                    </div>

                </div>
            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-circle-info"></i>
                    No hay asignaciones registradas todavía.
                </div>
            </div>
        <?php endif; ?>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .card {
            border-radius: 15px;
        }

        .card-header {
            font-size: 1.05rem;
        }

        ul li {
            font-size: 0.9rem;
        }

        .badge {
            font-size: 0.85rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/patrullas_asignaciones/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Reportes Diarios'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Reportes Diarios</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Descargas</h3>
            </div>

            <div class="card-body">

                <?php if(session('success')): ?>
                    <div class="alert alert-success mb-2"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger mb-2"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <form method="GET" action="<?php echo e(route('daily_reports.index')); ?>" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" value="<?php echo e($fecha); ?>" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Turno</label>
                            <input type="number" name="turno_id" value="<?php echo e($turno_id); ?>" class="form-control" min="1">
                            
                        </div>

                        <div class="col-md-6 d-flex align-items-end" style="gap:8px;">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fa-solid fa-filter"></i> Ver
                            </button>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear reportes')): ?>
                                <form action="<?php echo e(route('daily_reports.generar')); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="fecha" value="<?php echo e($fecha); ?>">
                                    <input type="hidden" name="turno_id" value="<?php echo e($turno_id); ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa-solid fa-bolt"></i> Generar todos
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $tipo = $t['tipo'];
                            $exists = $estado[$tipo]['exists'] ?? false;
                        ?>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-outline <?php echo e($exists ? 'card-success' : 'card-secondary'); ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php echo e($t['label']); ?>

                                    </h3>
                                    <div class="card-tools">
                                        <?php if($exists): ?>
                                            <span class="badge badge-success">Listo</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Se genera al descargar</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex" style="gap:8px; flex-wrap:wrap;">
                                        <a class="btn btn-success"
                                           href="<?php echo e(route('daily_reports.descargar', ['tipo' => $tipo, 'fecha' => $fecha, 'turno_id' => $turno_id])); ?>">
                                            <i class="fa-solid fa-download"></i> Descargar
                                        </a>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear reportes')): ?>
                                            <form action="<?php echo e(route('daily_reports.generar')); ?>" method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="fecha" value="<?php echo e($fecha); ?>">
                                                <input type="hidden" name="turno_id" value="<?php echo e($turno_id); ?>">
                                                <input type="hidden" name="tipos[]" value="<?php echo e($tipo); ?>">
                                                <button class="btn btn-outline-primary" type="submit">
                                                    <i class="fa-solid fa-gear"></i> Generar
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if($exists): ?>
                                            <button type="button" class="btn btn-outline-dark" disabled>
                                                <?php echo e($estado[$tipo]['name'] ?? ''); ?>

                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/daily_reports/index.blade.php ENDPATH**/ ?>
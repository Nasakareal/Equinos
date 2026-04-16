

<?php $__env->startSection('title', 'Estado de Fuerza'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Estado de Fuerza</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Reportes generados</h3>
    </div>

    <div class="card-body">

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" value="<?php echo e($fecha_desde); ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" value="<?php echo e($fecha_hasta); ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Turno</label>
                    <input type="number" name="turno_id" value="<?php echo e($turno_id); ?>" class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Turno</th>
                    <th>Archivo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($r->fecha); ?></td>
                        <td><?php echo e($r->turno_id); ?></td>
                        <td><?php echo e($r->archivo ?? '—'); ?></td>
                        <td>
                            <a href="<?php echo e(route('daily_reports.descargar', [$r->id, 'estado_fuerza'])); ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center">Sin registros</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php echo e($reportes->links()); ?>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/daily_reports/estado_fuerza/index.blade.php ENDPATH**/ ?>
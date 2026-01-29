

<?php $__env->startSection('title', 'Detalle Reporte Diario'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Reporte Diario</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <?php echo e(\Carbon\Carbon::parse($daily_report->fecha)->format('d/m/Y')); ?>

                    · <?php echo e($daily_report->tipo_reporte); ?>

                    · <?php echo e($daily_report->turno?->nombre ?? ('Turno #' . $daily_report->turno_id)); ?>

                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('daily_reports.index')); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success mb-2"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger mb-2"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <div class="mb-2">
                    <strong>Generado por:</strong>
                    <?php echo e($daily_report->generadoPor?->name ?? ('User #' . $daily_report->generado_por)); ?>

                    <br>
                    <strong>Notas:</strong> <?php echo e($daily_report->notas ?? '—'); ?>

                </div>

                <hr>

                
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo e($totales['total_filas'] ?? $daily_report->rows->count()); ?></h3>
                                <p>Total de filas</p>
                            </div>
                            <div class="icon"><i class="fas fa-list"></i></div>
                        </div>
                    </div>
                </div>

                <h5>Totales por dependencia</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Dependencia</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $porDep = $totales['por_dependencia'] ?? collect();
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $porDep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($dep ?: 'Sin dependencia'); ?></td>
                                    <td class="text-center"><?php echo e($t['total'] ?? 0); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="2"><center>Sin datos.</center></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <h5>Detalle</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Grado</th>
                                <th>CUIP</th>
                                <th>Dependencia</th>
                                <th>Celular</th>
                                <th>Cargo</th>
                                <th>CRP</th>
                                <th>Área/Sector</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $daily_report->rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($row->orden ?? $row->id); ?></td>
                                    <td>
                                        <?php echo e($row->nombre
                                            ?? $row->personal?->nombres
                                            ?? ('Personal #' . $row->personal_id)); ?>

                                    </td>
                                    <td><?php echo e($row->grado ?? $row->personal?->grado ?? '—'); ?></td>
                                    <td><?php echo e($row->cuip ?? $row->personal?->cuip ?? '—'); ?></td>
                                    <td><?php echo e($row->dependencia ?? $row->personal?->dependencia ?? '—'); ?></td>
                                    <td><?php echo e($row->celular ?? $row->personal?->celular ?? '—'); ?></td>
                                    <td><?php echo e($row->cargo ?? $row->personal?->cargo ?? '—'); ?></td>
                                    <td><?php echo e($row->crp ?? $row->personal?->crp ?? '—'); ?></td>
                                    <td><?php echo e($row->area_sector ?? $row->personal?->area_patrullaje ?? '—'); ?></td>
                                    <td><?php echo e($row->observaciones ?? '—'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10"><center>Sin filas.</center></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/daily_reports/show.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Reportes Diarios'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Reportes Diarios</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Historial</h3>

                <div class="card-tools d-flex" style="gap:8px;">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear reportes')): ?>
                        <form action="<?php echo e(route('daily_reports.generar')); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-file-circle-plus"></i> Generar hoy
                            </button>
                        </form>
                    <?php endif; ?>

                    <a href="<?php echo e(route('daily_reports.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate"></i> Refrescar
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

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th><center>#</center></th>
                            <th><center>Fecha</center></th>
                            <th><center>Tipo</center></th>
                            <th><center>Turno</center></th>
                            <th><center>Generado por</center></th>
                            <th style="width:280px;"><center>Descarga Armamento</center></th>
                            <th style="width:90px;"><center>Ver</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                // Si NO traes rows en index, esto hará N+1.
                                // Ideal: en el controller index() agrega withCount o un eager load básico.
                                // Aquí lo dejamos simple, pero funcional.
                                $deps = $r->rows()
                                    ->select('dependencia')
                                    ->whereNotNull('dependencia')
                                    ->where('dependencia','!=','')
                                    ->distinct()
                                    ->orderBy('dependencia')
                                    ->pluck('dependencia');

                                $dep_default = $deps->first();
                            ?>

                            <tr>
                                <td><center><?php echo e($r->id); ?></center></td>
                                <td><center><?php echo e(\Carbon\Carbon::parse($r->fecha)->format('d/m/Y')); ?></center></td>
                                <td><center><?php echo e($r->tipo_reporte); ?></center></td>
                                <td><center><?php echo e($r->turno?->nombre ?? ('Turno #' . $r->turno_id)); ?></center></td>
                                <td><center><?php echo e($r->generadoPor?->name ?? ('User #' . $r->generado_por)); ?></center></td>

                                <td>
                                    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
                                        <select class="form-control form-control-sm js-dep" style="max-width: 220px;" <?php echo e($deps->isEmpty() ? 'disabled' : ''); ?>>
                                            <?php $__empty_2 = true; $__currentLoopData = $deps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <option value="<?php echo e($d); ?>" <?php echo e($d === $dep_default ? 'selected' : ''); ?>>
                                                    <?php echo e($d); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <option value="">Sin dependencia</option>
                                            <?php endif; ?>
                                        </select>

                                        <a
                                            href="<?php echo e($deps->isEmpty()
                                                ? '#'
                                                : route('daily_reports.descargar', ['daily_report' => $r->id, 'tipo' => 'excel_armamento']) . '?dependencia=' . urlencode($dep_default)); ?>"
                                            class="btn btn-success btn-sm js-btn-excel <?php echo e($deps->isEmpty() ? 'disabled' : ''); ?>"
                                            title="Descargar Excel Armamento"
                                        >
                                            <i class="fa-solid fa-file-excel"></i>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <center>
                                        <a href="<?php echo e(route('daily_reports.show', $r->id)); ?>" class="btn btn-info btn-sm" title="Ver reporte">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7"><center>Sin reportes todavía.</center></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr').forEach(function (tr) {
        const sel = tr.querySelector('.js-dep');
        const btn = tr.querySelector('.js-btn-excel');
        if (!sel || !btn) return;

        const baseHref = btn.getAttribute('href');
        if (!baseHref || baseHref === '#') return;

        sel.addEventListener('change', function () {
            const dep = sel.value || '';
            const clean = baseHref.split('?')[0];
            btn.setAttribute('href', clean + '?dependencia=' + encodeURIComponent(dep));
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/daily_reports/index.blade.php ENDPATH**/ ?>
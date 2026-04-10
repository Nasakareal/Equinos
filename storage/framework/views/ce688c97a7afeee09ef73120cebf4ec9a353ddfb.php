

<?php $__env->startSection('title', 'Reportes del Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Reportes del Servicio</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Listado de reportes operativos capturados para este servicio
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="<?php echo e(route('mis_servicios.show', $servicio->id)); ?>" class="btn btn-secondary shadow-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver al servicio
            </a>

            <a href="<?php echo e(route('mis_servicios.reportes.create', $servicio->id)); ?>" class="btn btn-primary shadow-sm">
                <i class="fa-solid fa-plus mr-1"></i> Nuevo reporte
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $tipoServicioClass = match (strtoupper((string) $servicio->tipo_servicio)) {
            'SEGURIDAD' => 'badge badge-info',
            'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
            'BUSQUEDA' => 'badge badge-danger',
            'DESFILES' => 'badge badge-purple',
            'PROXIMIDAD SOCIAL' => 'badge badge-success',
            'ACTOS CIVICOS' => 'badge badge-warning',
            'OTRO' => 'badge badge-secondary',
            default => 'badge badge-secondary',
        };
    ?>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Datos base del servicio</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">ID servicio</div>
                        <div class="info-value">#<?php echo e($servicio->id); ?></div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Fecha</div>
                        <div class="info-value">
                            <?php echo e($servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') : '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Hora</div>
                        <div class="info-value">
                            <?php echo e($servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de servicio</div>
                        <div class="info-value">
                            <span class="<?php echo e($tipoServicioClass); ?> badge-pill px-3 py-2">
                                <?php echo e($servicio->tipo_servicio ?? '-'); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-card">
                        <div class="info-label">Municipio</div>
                        <div class="info-value"><?php echo e($servicio->municipio ?? '-'); ?></div>
                    </div>
                </div>

                <div class="col-md-8 mb-3">
                    <div class="info-card">
                        <div class="info-label">Lugar base</div>
                        <div class="info-value"><?php echo e($servicio->lugar ?? '-'); ?></div>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="info-card">
                        <div class="info-label">Asunto base</div>
                        <div class="info-value"><?php echo e($servicio->asunto ?? '-'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Reportes capturados</h3>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($reportes->count()): ?>
                <div class="table-responsive">
                    <table id="tablaReportesServicio" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo de reporte</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Municipio</th>
                                <th>Asunto</th>
                                <th>Lugar</th>
                                <th>Fotos</th>
                                <th style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reporte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($reporte->id); ?></td>
                                    <td>
                                        <span class="badge badge-secondary px-3 py-2">
                                            <?php echo e($reporte->tipo_reporte ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php echo e($reporte->fecha ? \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') : '-'); ?>

                                    </td>
                                    <td>
                                        <?php echo e($reporte->hora ? \Carbon\Carbon::parse($reporte->hora)->format('H:i') : '-'); ?>

                                    </td>
                                    <td><?php echo e($reporte->municipio ?? '-'); ?></td>
                                    <td><?php echo e($reporte->asunto ?? '-'); ?></td>
                                    <td><?php echo e($reporte->lugar ?? '-'); ?></td>
                                    <td><?php echo e($reporte->fotos_count ?? ($reporte->fotos->count() ?? 0)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('mis_servicios.reportes.show', [$servicio->id, $reporte->id])); ?>" class="btn btn-info btn-sm">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="<?php echo e(route('mis_servicios.reportes.edit', [$servicio->id, $reporte->id])); ?>" class="btn btn-success btn-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>

                                            <?php if(Route::has('mis_servicios.reportes.destroy')): ?>
                                                <form action="<?php echo e(route('mis_servicios.reportes.destroy', [$servicio->id, $reporte->id])); ?>" method="POST" class="formEliminarReporte d-inline-block">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border text-center mb-0">
                    <i class="fa-regular fa-folder-open mr-1"></i>
                    Aún no hay reportes capturados para este servicio.
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function () {
        $('#tablaReportesServicio').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-MX.json'
            },
            order: [[0, 'desc']]
        });

        $(document).on('submit', '.formEliminarReporte', function (e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: '¿Eliminar este reporte?',
                text: 'Esta acción no se puede revertir.',
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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/mis_servicios/reportes/index.blade.php ENDPATH**/ ?>
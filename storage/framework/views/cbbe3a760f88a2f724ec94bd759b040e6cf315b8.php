

<?php $__env->startSection('title', 'Mis Servicios'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Mis Servicios</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $fechaSeleccionada = request('fecha') ?? now()->toDateString();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-success">

            <div class="card-header">
                <h3 class="card-title">Servicios y apoyos asignados / disponibles para captura</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('servicios.index')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver a administración
                    </a>
                </div>
            </div>

            <div class="card-body">

                
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center" style="gap:10px;">
                        <form method="GET" action="<?php echo e(route('mis_servicios.index')); ?>" class="d-flex" style="gap:10px;">
                            <input type="date" name="fecha" value="<?php echo e($fechaSeleccionada); ?>" class="form-control">
                            <button class="btn btn-primary">Filtrar</button>
                        </form>

                        <a href="<?php echo e(route('mis_servicios.index', ['fecha' => now()->toDateString()])); ?>" class="btn btn-secondary">
                            Hoy
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="mis_servicios" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th><center>Número</center></th>
                                <th><center>Fecha</center></th>
                                <th><center>Hora</center></th>
                                <th><center>Categoría</center></th>
                                <th><center>Tipo de servicio</center></th>
                                <th><center>Asunto</center></th>
                                <th><center>Municipio</center></th>
                                <th><center>Lugar</center></th>
                                <th><center>Reportes</center></th>
                                <th><center>Acciones</center></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $servicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="text-align: center"><?php echo e($index + 1); ?></td>
                                    <td><?php echo e(optional($servicio->fecha)->format('d/m/Y')); ?></td>
                                    <td><?php echo e($servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-'); ?></td>
                                    <td><?php echo e($servicio->categoria_registro ?? '-'); ?></td>
                                    <td><?php echo e($servicio->tipo_servicio ?? '-'); ?></td>
                                    <td><?php echo e($servicio->asunto ?? '-'); ?></td>
                                    <td><?php echo e($servicio->municipio ?? '-'); ?></td>
                                    <td><?php echo e($servicio->lugar ?? '-'); ?></td>
                                    <td style="text-align: center"><?php echo e($servicio->reportes->count()); ?></td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver reportes de servicios')): ?>
                                                <a href="<?php echo e(route('mis_servicios.show', $servicio->id)); ?>" class="btn btn-info btn-sm">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear reportes de servicios')): ?>
                                                <a href="<?php echo e(route('mis_servicios.reportes.create', $servicio->id)); ?>" class="btn btn-primary btn-sm">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver reportes de servicios')): ?>
                                                <a href="<?php echo e(route('mis_servicios.reportes.index', $servicio->id)); ?>" class="btn btn-secondary btn-sm">
                                                    <i class="fa-solid fa-file-lines"></i>
                                                </a>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
<script>
    $(function () {
        const dt = $('#mis_servicios').DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ total registros)",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscador:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            scrollX: true,
            deferRender: true
        });

        setTimeout(function () {
            dt.columns.adjust().responsive.recalc();
        }, 150);
    });

    <?php if(session('success')): ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '<?php echo e(session('success')); ?>',
            showConfirmButton: false,
            timer: 12000
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/mis_servicios/index.blade.php ENDPATH**/ ?>
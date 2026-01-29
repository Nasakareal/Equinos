



<?php $__env->startSection('title', 'Detalle del Horario de Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle del Horario de Servicio</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title">Información del Horario</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                        <a href="<?php echo e(route('servicio.edit', $service_schedule->id)); ?>" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('servicio.index')); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- ID -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->id); ?>" readonly>
                        </div>
                    </div>

                    <!-- Turno -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Turno</label>
                            <input type="text"
                                   class="form-control"
                                   value="<?php echo e($service_schedule->turno->nombre ?? '-'); ?> <?php echo e(isset($service_schedule->turno->clave) ? '(' . $service_schedule->turno->clave . ')' : ''); ?>"
                                   readonly>
                        </div>
                    </div>

                    <!-- Tolerancia -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tolerancia (min)</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->min_tolerancia); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Hora entrada -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Hora de entrada</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->hora_entrada ?? '-'); ?>" readonly>
                        </div>
                    </div>

                    <!-- Hora salida -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Hora de salida</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->hora_salida ?? '-'); ?>" readonly>
                        </div>
                    </div>

                    <!-- Cruza día -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cruza día</label>
                            <div class="mt-2">
                                <?php if((int)$service_schedule->cruza_dia === 1): ?>
                                    <span class="badge badge-warning p-2">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary p-2">No</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Registro -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Registro</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->created_at ?? '-'); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Notas -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Notas</label>
                            <textarea class="form-control" rows="3" readonly><?php echo e($service_schedule->notas ?? '-'); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Actualización -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Última actualización</label>
                            <input type="text" class="form-control" value="<?php echo e($service_schedule->updated_at ?? '-'); ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group" role="group">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                                <a href="<?php echo e(route('servicio.edit', $service_schedule->id)); ?>" class="btn btn-success">
                                    <i class="fa-regular fa-pen-to-square"></i> Editar
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                                <form action="<?php echo e(route('servicio.destroy', $service_schedule->id)); ?>"
                                      method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn btn-danger delete-btn">
                                        <i class="fa-regular fa-trash-can"></i> Eliminar
                                    </button>
                                </form>
                            <?php endif; ?>

                            <a href="<?php echo e(route('servicio.index')); ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Volver al listado
                            </a>

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
    .form-group label { font-weight: bold; }
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
            timer: 12000
        });
    <?php endif; ?>

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Eliminar este horario de servicio?',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicio/show.blade.php ENDPATH**/ ?>
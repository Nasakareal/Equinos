



<?php $__env->startSection('title', 'Detalle del Turno'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle del Turno</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Información del Turno</h3>

                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                            <a href="<?php echo e(route('turnos.edit', $turno->id)); ?>" class="btn btn-success btn-sm">
                                <i class="fa-regular fa-pen-to-square"></i> Editar
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('turnos.index')); ?>" class="btn btn-secondary btn-sm">
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
                                <input type="text" class="form-control" value="<?php echo e($turno->id); ?>" readonly>
                            </div>
                        </div>

                        <!-- Clave -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Clave</label>
                                <input type="text" class="form-control" value="<?php echo e($turno->clave ?? '-'); ?>" readonly>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="mt-2">
                                    <?php if(!empty($turno->activo) && (int)$turno->activo === 1): ?>
                                        <span class="badge badge-success p-2">ACTIVO</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary p-2">INACTIVO</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Registro</label>
                                <input type="text" class="form-control" value="<?php echo e($turno->created_at ?? '-'); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" value="<?php echo e($turno->nombre ?? '-'); ?>" readonly>
                            </div>
                        </div>

                        <!-- Actualización -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Última actualización</label>
                                <input type="text" class="form-control" value="<?php echo e($turno->updated_at ?? '-'); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Descripción -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" rows="3" readonly><?php echo e($turno->descripcion ?? '-'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                                    <a href="<?php echo e(route('turnos.edit', $turno->id)); ?>" class="btn btn-success">
                                        <i class="fa-regular fa-pen-to-square"></i> Editar
                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar turnos')): ?>
                                    <form action="<?php echo e(route('turnos.destroy', $turno->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="btn btn-danger delete-btn">
                                            <i class="fa-regular fa-trash-can"></i> Eliminar
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <a href="<?php echo e(route('turnos.index')); ?>" class="btn btn-secondary">
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
                title: '¿Estás seguro de eliminar este registro?',
                text: "¡No podrás revertir esta acción!",
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/turnos/show.blade.php ENDPATH**/ ?>
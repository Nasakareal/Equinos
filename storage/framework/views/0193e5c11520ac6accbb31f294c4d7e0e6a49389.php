

<?php $__env->startSection('title', 'Gestionar Permisos'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Gestionar Permisos para el Rol: <?php echo e($role->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Asignar Permisos</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('roles.assignPermissions', $role->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Descripción del Permiso</center></th>
                                    <th><center>Asignar</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($permission->name); ?></td>
                                        <td style="text-align: center">
                                            <input type="checkbox" name="permissions[]" value="<?php echo e($permission->id); ?>"
                                                <?php echo e(in_array($permission->id, $rolePermissions) ? 'checked' : ''); ?>>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="form-group mt-3 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i> Guardar Permisos
                            </button>
                            <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-ban"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
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
                timer: 1500
            });
        <?php endif; ?>

        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                html: `
                    <ul style="text-align: left;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                `,
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/roles/permissions.blade.php ENDPATH**/ ?>
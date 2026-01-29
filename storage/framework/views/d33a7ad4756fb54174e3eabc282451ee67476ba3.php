

<?php $__env->startSection('title', 'Detalles del Usuario'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalles del Usuario</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos Registrados</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <p class="form-control-static"><?php echo e($user->name); ?></p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <p class="form-control-static"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Área -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Área</label>
                                <p class="form-control-static"><?php echo e($user->area ?? 'No especificada'); ?></p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <p class="form-control-static"><?php echo e($user->estado); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rol -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rol</label>
                                <p class="form-control-static">
                                    <?php echo e($user->roles->pluck('name')->join(', ')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Unidad principal -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unidad principal</label>
                                <p class="form-control-static">
                                    <?php echo e(optional($user->unidad)->nombre ?? 'Sin unidad asignada'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Turno -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Turno</label>
                                <p class="form-control-static">
                                    <?php echo e(optional($user->turno)->nombre ?? 'Sin turno asignado'); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Patrulla -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patrulla (número económico)</label>
                                <p class="form-control-static">
                                    <?php echo e(optional($user->patrulla)->numero_economico ?? 'Sin patrulla asignada'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if($user->hasRole('Coordinador')): ?>
                        <div class="row">
                            <!-- Unidades adicionales -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Unidades a cargo</label>
                                    <?php if($user->unidades && $user->unidades->count()): ?>
                                        <ul class="mb-0">
                                            <?php $__currentLoopData = $user->unidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($u->nombre); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="form-control-static">No tiene unidades adicionales asignadas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Foto de perfil -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Foto de Perfil</label>
                                <?php if($user->foto_perfil): ?>
                                    <div>
                                        <img src="<?php echo e(asset('storage/' . $user->foto_perfil)); ?>"
                                             alt="Foto de Perfil"
                                             class="img-thumbnail"
                                             style="max-width: 150px;">
                                    </div>
                                <?php else: ?>
                                    <p class="form-control-static">No tiene foto de perfil.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Volver
                            </a>
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
        .form-control-static {
            display: block;
            font-size: 1rem;
            margin-top: 0.4rem;
        }
        .img-thumbnail {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 4px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        console.log('Vista show de usuario cargada correctamente');
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/users/show.blade.php ENDPATH**/ ?>
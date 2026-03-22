

<?php $__env->startSection('title', 'Crear Área'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Crear Nueva Área</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Registro de Área</h3>
                </div>

                <div class="card-body">
                    <form action="<?php echo e(route('areas.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        
                        <div class="form-group">
                            <label for="clave">Clave del Área</label>
                            <input type="text"
                                   name="clave"
                                   id="clave"
                                   class="form-control <?php $__errorArgs = ['clave'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('clave')); ?>"
                                   placeholder="Ej. CANINA, OPERATIVA..."
                                   required>
                            <?php $__errorArgs = ['clave'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group">
                            <label for="nombre">Nombre del Área</label>
                            <input type="text"
                                   name="nombre"
                                   id="nombre"
                                   class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('nombre')); ?>"
                                   placeholder="Ej. ÁREA CANINA"
                                   required>
                            <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group">
                            <label for="activo">Estatus</label>
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="activo"
                                       name="activo"
                                       value="1"
                                       <?php echo e(old('activo', 1) ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="activo">Área Activa</label>
                            </div>
                        </div>

                        <hr>

                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i> Guardar Área
                            </button>

                            <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-ban"></i> Cancelar
                            </a>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    <?php if($errors->any()): ?>
        Swal.fire({
            icon: 'error',
            title: 'Errores en el formulario',
            html: `
                <ul style="text-align:left;">
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/areas/create.blade.php ENDPATH**/ ?>
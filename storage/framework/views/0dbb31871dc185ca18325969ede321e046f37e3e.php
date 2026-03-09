

<?php $__env->startSection('title', 'Registrar Incidencia'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Registrar Incidencia · <?php echo e($animal->nombre); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Nueva incidencia</h3>

                <div class="card-tools">
                    <a href="<?php echo e(url('/animales/'.$animal->id)); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <form action="<?php echo e(route('animales.incidencias.store', $animal->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="datetime-local"
                                       name="fecha"
                                       value="<?php echo e(old('fecha', now()->format('Y-m-d\TH:i'))); ?>"
                                       class="form-control <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       required>
                                <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo de incidencia</label>
                                <select name="incidence_type_id" class="form-control <?php $__errorArgs = ['incidence_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- Selecciona --</option>
                                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>" <?php echo e(old('incidence_type_id') == $type->id ? 'selected' : ''); ?>>
                                            <?php echo e($type->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['incidence_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">
                                    Solo se muestran tipos de incidencia para animales.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gravedad</label>
                                <select name="gravedad" class="form-control <?php $__errorArgs = ['gravedad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="BAJA" <?php echo e(old('gravedad') == 'BAJA' ? 'selected' : ''); ?>>BAJA</option>
                                    <option value="MEDIA" <?php echo e(old('gravedad') == 'MEDIA' ? 'selected' : ''); ?>>MEDIA</option>
                                    <option value="ALTA" <?php echo e(old('gravedad') == 'ALTA' ? 'selected' : ''); ?>>ALTA</option>
                                </select>
                                <?php $__errorArgs = ['gravedad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion"
                                          rows="4"
                                          class="form-control <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Describe la incidencia"><?php echo e(old('descripcion')); ?></textarea>
                                <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-warning">
                        <i class="fa-solid fa-check"></i> Guardar
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>

input.form-control,
textarea.form-control,
select.form-control {
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    background-color: #25364a !important;
    color: #ffffff !important;
    border-color: #f39c12 !important;
    box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

::placeholder {
    color: #b8c7ce !important;
    opacity: 1;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

.btn-warning {
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(243, 156, 18, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(243, 156, 18, 0.45);
}

.btn-warning:focus,
.btn-warning:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.35) !important;
}

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
<?php if($errors->any()): ?>
Swal.fire({
    icon: 'error',
    title: 'Revisa el formulario',
    html: `<?php echo implode('<br>', $errors->all()); ?>`
});
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/animales/incidencias/create.blade.php ENDPATH**/ ?>
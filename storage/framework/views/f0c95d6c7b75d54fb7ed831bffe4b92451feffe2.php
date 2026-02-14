

<?php $__env->startSection('title', 'Turno en servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Turno en servicio</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('settings.turno_actual.update')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label>Turno que está laborando</label>
                <select name="turno_actual_id" class="form-control" required>
                    <?php $__currentLoopData = $turnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->id); ?>" <?php echo e((int)$turno_actual_id === (int)$t->id ? 'selected' : ''); ?>>
                            <?php echo e($t->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <button class="btn btn-primary mt-2">Guardar</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    select.form-control{
        background: rgba(0,0,0,.22) !important;
        color: rgba(234,240,255,.95) !important;
        border: 1px solid rgba(255,255,255,.18) !important;
    }

    select.form-control:focus{
        border-color: rgba(45,168,255,.55) !important;
        box-shadow: 0 0 0 .2rem rgba(45,168,255,.18) !important;
        outline: none !important;
    }

    select.form-control option{
        background: #ffffff !important;
        color: #111111 !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/turno_actual.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Nueva Asignación de Armamento'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Nueva Asignación de Armamento</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // ✅ Prioridad: old() si hay errores; si no, el que viene por query (?personal_id=)
    $personalSeleccionado = old('personal_id', $personal_id_preseleccionado ?? '');
    $bloquearPersonal = !empty($personal_id_preseleccionado) && empty(old('personal_id'));
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Llene los Datos</h3>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('armamento_asignaciones.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    
                    <?php if($bloquearPersonal): ?>
                        <input type="hidden" name="personal_id" value="<?php echo e($personalSeleccionado); ?>">
                    <?php endif; ?>

                    <div class="row">
                        <!-- Personal -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="personal_id">Personal</label>
                                <select name="personal_id"
                                        id="personal_id"
                                        class="form-control <?php $__errorArgs = ['personal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required
                                        <?php echo e($bloquearPersonal ? 'disabled' : ''); ?>>
                                    <option value="" disabled <?php echo e(empty($personalSeleccionado) ? 'selected' : ''); ?>>
                                        Seleccione...
                                    </option>

                                    <?php $__currentLoopData = $personals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->id); ?>"
                                            <?php echo e((string)$personalSeleccionado === (string)$p->id ? 'selected' : ''); ?>>
                                            <?php echo e($p->nombres); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <?php $__errorArgs = ['personal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                <?php if($bloquearPersonal): ?>
                                    <small class="text-muted">
                                        Personal fijado porque llegaste desde el detalle del elemento.
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Arma -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weapon_id">Arma</label>
                                <select name="weapon_id"
                                        id="weapon_id"
                                        class="form-control <?php $__errorArgs = ['weapon_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <?php $__currentLoopData = $weapons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($w->id); ?>"
                                            <?php echo e(old('weapon_id') == $w->id ? 'selected' : ''); ?>>
                                            <?php echo e($w->tipo); ?> — <?php echo e($w->matricula); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['weapon_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">
                                    Solo puede haber una asignación activa por arma.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Fecha asignación -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_asignacion">Fecha de asignación</label>
                                <input type="date"
                                       name="fecha_asignacion"
                                       id="fecha_asignacion"
                                       class="form-control <?php $__errorArgs = ['fecha_asignacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('fecha_asignacion')); ?>">
                                <?php $__errorArgs = ['fecha_asignacion'];
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
                        </div>

                        <!-- Fecha devolución -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_devolucion">Fecha de devolución</label>
                                <input type="date"
                                       name="fecha_devolucion"
                                       id="fecha_devolucion"
                                       class="form-control <?php $__errorArgs = ['fecha_devolucion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('fecha_devolucion')); ?>">
                                <?php $__errorArgs = ['fecha_devolucion'];
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
                        </div>

                        <!-- Estatus -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Estatus</label>
                                <select name="status"
                                        id="status"
                                        class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="ASIGNADA" <?php echo e(old('status', 'ASIGNADA') === 'ASIGNADA' ? 'selected' : ''); ?>>
                                        ASIGNADA
                                    </option>
                                    <option value="DEVUELTA" <?php echo e(old('status') === 'DEVUELTA' ? 'selected' : ''); ?>>
                                        DEVUELTA
                                    </option>
                                    <option value="CANCELADA" <?php echo e(old('status') === 'CANCELADA' ? 'selected' : ''); ?>>
                                        CANCELADA
                                    </option>
                                </select>
                                <?php $__errorArgs = ['status'];
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
                        </div>
                    </div>

                    <div class="row">
                        <!-- Observaciones -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="3"
                                          class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Notas adicionales..."><?php echo e(old('observaciones')); ?></textarea>
                                <?php $__errorArgs = ['observaciones'];
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
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Registrar asignación
                    </button>

                    <a href="<?php echo e(route('armamento_asignaciones.index')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-ban"></i> Cancelar
                    </a>

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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/armamento_asignaciones/create.blade.php ENDPATH**/ ?>
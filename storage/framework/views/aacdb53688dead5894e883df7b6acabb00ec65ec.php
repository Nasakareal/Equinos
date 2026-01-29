



<?php $__env->startSection('title', 'Crear Horario de Turno'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Crear Horario de Turno</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Llene los Datos</h3>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('turno_horarios.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <!-- Turno -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="turno_id">Turno</label>
                                <select name="turno_id"
                                        id="turno_id"
                                        class="form-control <?php $__errorArgs = ['turno_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <?php $__currentLoopData = $turnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($turno->id); ?>" <?php echo e(old('turno_id') == $turno->id ? 'selected' : ''); ?>>
                                            <?php echo e($turno->nombre); ?> (<?php echo e($turno->clave); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['turno_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Hora entrada -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_entrada">Hora de entrada</label>
                                <input type="time"
                                       name="hora_entrada"
                                       id="hora_entrada"
                                       class="form-control <?php $__errorArgs = ['hora_entrada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('hora_entrada')); ?>">
                                <?php $__errorArgs = ['hora_entrada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Opcional.</small>
                            </div>
                        </div>

                        <!-- Hora salida -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_salida">Hora de salida</label>
                                <input type="time"
                                       name="hora_salida"
                                       id="hora_salida"
                                       class="form-control <?php $__errorArgs = ['hora_salida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('hora_salida')); ?>">
                                <?php $__errorArgs = ['hora_salida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Opcional.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tolerancia -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_tolerancia">Minutos de tolerancia</label>
                                <input type="number"
                                       name="min_tolerancia"
                                       id="min_tolerancia"
                                       class="form-control <?php $__errorArgs = ['min_tolerancia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('min_tolerancia', 0)); ?>"
                                       min="0"
                                       max="1440"
                                       required>
                                <?php $__errorArgs = ['min_tolerancia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">0 = sin tolerancia.</small>
                            </div>
                        </div>

                        <!-- Cruza día -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cruza_dia">Cruza día</label>

                                
                                <input type="hidden" name="cruza_dia" value="0">

                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="cruza_dia"
                                           name="cruza_dia"
                                           value="1"
                                           <?php echo e(old('cruza_dia') ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="cruza_dia">Sí</label>
                                </div>

                                <?php $__errorArgs = ['cruza_dia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Márcalo si la salida corresponde al día siguiente.</small>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="notas">Notas</label>
                                <input type="text"
                                       name="notas"
                                       id="notas"
                                       class="form-control <?php $__errorArgs = ['notas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('notas')); ?>"
                                       placeholder="Opcional (máx. 255)">
                                <?php $__errorArgs = ['notas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check"></i> Registrar
                                </button>
                                <a href="<?php echo e(route('turno_horarios.index')); ?>" class="btn btn-secondary">
                                    <i class="fa-solid fa-ban"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </div>

                </form>
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/turno_horarios/create.blade.php ENDPATH**/ ?>
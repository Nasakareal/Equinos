

<?php $__env->startSection('title', 'Editar Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Editar Servicio / Apoyo / Memorándum</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(url('/servicios/' . $servicio->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="card card-outline card-primary shadow-sm">

        <div class="card-header">
            <h3 class="card-title">Datos del servicio</h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de registro</label>
                        <select name="categoria_registro" class="form-control <?php $__errorArgs = ['categoria_registro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO" <?php echo e(old('categoria_registro', $servicio->categoria_registro) == 'SERVICIO' ? 'selected' : ''); ?>>SERVICIO</option>
                            <option value="APOYO" <?php echo e(old('categoria_registro', $servicio->categoria_registro) == 'APOYO' ? 'selected' : ''); ?>>APOYO</option>
                            <option value="MEMORANDUM" <?php echo e(old('categoria_registro', $servicio->categoria_registro) == 'MEMORANDUM' ? 'selected' : ''); ?>>MEMORANDUM</option>
                        </select>
                        <?php $__errorArgs = ['categoria_registro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control <?php $__errorArgs = ['tipo_servicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'SEGURIDAD' ? 'selected' : ''); ?>>SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'BARRIDOS DE SEGURIDAD' ? 'selected' : ''); ?>>BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'BUSQUEDA' ? 'selected' : ''); ?>>BUSQUEDA</option>
                            <option value="DESFILES" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'DESFILES' ? 'selected' : ''); ?>>DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'PROXIMIDAD SOCIAL' ? 'selected' : ''); ?>>PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS" <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'ACTOS CIVICOS' ? 'selected' : ''); ?>>ACTOS CIVICOS</option>
                        </select>
                        <?php $__errorArgs = ['tipo_servicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número / Referencia</label>
                        <input type="text" name="folio_referencia" class="form-control <?php $__errorArgs = ['folio_referencia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('folio_referencia', $servicio->folio_referencia)); ?>">
                        <?php $__errorArgs = ['folio_referencia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input
                            type="date"
                            name="fecha"
                            class="form-control <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('fecha', $servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('Y-m-d') : '')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

            </div>

            <div class="row mt-3">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input
                            type="time"
                            name="hora"
                            class="form-control <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('hora', $servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-4 <?php echo e(old('tipo_servicio', $servicio->tipo_servicio) == 'BUSQUEDA' ? '' : 'd-none'); ?>" id="bloque_busqueda">
                    <div class="form-group">
                        <label>Tipo de búsqueda</label>
                        <select name="tipo_busqueda" class="form-control <?php $__errorArgs = ['tipo_busqueda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Seleccione</option>
                            <option value="EN VIDA" <?php echo e(old('tipo_busqueda', $servicio->tipo_busqueda) == 'EN VIDA' ? 'selected' : ''); ?>>EN VIDA</option>
                            <option value="RECURSO HUMANO" <?php echo e(old('tipo_busqueda', $servicio->tipo_busqueda) == 'RECURSO HUMANO' ? 'selected' : ''); ?>>RECURSO HUMANO</option>
                            <option value="EXPLOSIVO" <?php echo e(old('tipo_busqueda', $servicio->tipo_busqueda) == 'EXPLOSIVO' ? 'selected' : ''); ?>>EXPLOSIVO</option>
                            <option value="FORENSE" <?php echo e(old('tipo_busqueda', $servicio->tipo_busqueda) == 'FORENSE' ? 'selected' : ''); ?>>FORENSE</option>
                            <option value="NARCOTICOS" <?php echo e(old('tipo_busqueda', $servicio->tipo_busqueda) == 'NARCOTICOS' ? 'selected' : ''); ?>>NARCOTICOS</option>
                        </select>
                        <?php $__errorArgs = ['tipo_busqueda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Elemento</label>
                        <select name="personal_id" class="form-control <?php $__errorArgs = ['personal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $personales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('personal_id', $servicio->personal_id) == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->nombres); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['personal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Canino</label>
                        <select name="canino_id" class="form-control <?php $__errorArgs = ['canino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $caninos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e(old('canino_id', $servicio->canino_id) == $c->id ? 'selected' : ''); ?>>
                                    <?php echo e($c->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['canino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Equino</label>
                        <select name="equino_id" class="form-control <?php $__errorArgs = ['equino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $equinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($e->id); ?>" <?php echo e(old('equino_id', $servicio->equino_id) == $e->id ? 'selected' : ''); ?>>
                                    <?php echo e($e->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['equino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Patrulla</label>
                        <select name="patrulla_id" class="form-control <?php $__errorArgs = ['patrulla_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $patrullas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('patrulla_id', $servicio->patrulla_id) == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->nombre ?? $p->numero ?? $p->placas ?? 'ID '.$p->id); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['patrulla_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>¿Se cumplió?</label>
                        <select name="cumplio" class="form-control <?php $__errorArgs = ['cumplio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="1" <?php echo e(old('cumplio', $servicio->cumplio) == 1 ? 'selected' : ''); ?>>Sí</option>
                            <option value="0" <?php echo e(old('cumplio', $servicio->cumplio) == 0 ? 'selected' : ''); ?>>No</option>
                        </select>
                        <?php $__errorArgs = ['cumplio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="4" class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('observaciones', $servicio->observaciones)); ?></textarea>
                        <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="<?php echo e(url('/servicios')); ?>" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>
        </div>

    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .card { border-radius: 14px; }
    .btn { border-radius: 10px; }
    label { font-weight: 600; }
    .form-control { border-radius: 10px; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    function toggleBusqueda() {
        let val = $('#tipo_servicio').val();
        $('#bloque_busqueda').addClass('d-none');
        if (val === 'BUSQUEDA') {
            $('#bloque_busqueda').removeClass('d-none');
        }
    }

    $(function () {
        toggleBusqueda();
        $('#tipo_servicio').on('change', function () {
            toggleBusqueda();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicios/edit.blade.php ENDPATH**/ ?>
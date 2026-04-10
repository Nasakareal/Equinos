

<?php $__env->startSection('title', 'Registrar Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Registrar Servicio / Apoyo / Memorándum</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(url('/servicios')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Datos del servicio</h3>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de registro</label>
                        <select name="categoria_registro" id="categoria_registro" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO" <?php echo e(old('categoria_registro') == 'SERVICIO' ? 'selected' : ''); ?>>SERVICIO</option>
                            <option value="APOYO" <?php echo e(old('categoria_registro') == 'APOYO' ? 'selected' : ''); ?>>APOYO</option>
                            <option value="MEMORANDUM" <?php echo e(old('categoria_registro') == 'MEMORANDUM' ? 'selected' : ''); ?>>MEMORANDUM</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD" <?php echo e(old('tipo_servicio') == 'SEGURIDAD' ? 'selected' : ''); ?>>SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD" <?php echo e(old('tipo_servicio') == 'BARRIDOS DE SEGURIDAD' ? 'selected' : ''); ?>>BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA" <?php echo e(old('tipo_servicio') == 'BUSQUEDA' ? 'selected' : ''); ?>>BUSQUEDA</option>
                            <option value="DESFILES" <?php echo e(old('tipo_servicio') == 'DESFILES' ? 'selected' : ''); ?>>DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL" <?php echo e(old('tipo_servicio') == 'PROXIMIDAD SOCIAL' ? 'selected' : ''); ?>>PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS" <?php echo e(old('tipo_servicio') == 'ACTOS CIVICOS' ? 'selected' : ''); ?>>ACTOS CIVICOS</option>
                            <option value="OTRO" <?php echo e(old('tipo_servicio') == 'OTRO' ? 'selected' : ''); ?>>OTRO</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número / Referencia</label>
                        <input type="text" name="folio_referencia" class="form-control" value="<?php echo e(old('folio_referencia')); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo e(old('fecha')); ?>" required>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time" name="hora" class="form-control" value="<?php echo e(old('hora')); ?>" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Municipio</label>
                        <input type="text" name="municipio" class="form-control" value="<?php echo e(old('municipio')); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lugar</label>
                        <input type="text" name="lugar" class="form-control" value="<?php echo e(old('lugar')); ?>">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Asunto base</label>
                        <input type="text" name="asunto" id="asunto" class="form-control" value="<?php echo e(old('asunto')); ?>">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 d-none" id="bloque_busqueda">
                    <div class="form-group">
                        <label>Tipo de búsqueda</label>
                        <select name="tipo_busqueda" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="EN VIDA" <?php echo e(old('tipo_busqueda') == 'EN VIDA' ? 'selected' : ''); ?>>EN VIDA</option>
                            <option value="RECURSO HUMANO" <?php echo e(old('tipo_busqueda') == 'RECURSO HUMANO' ? 'selected' : ''); ?>>RECURSO HUMANO</option>
                            <option value="EXPLOSIVO" <?php echo e(old('tipo_busqueda') == 'EXPLOSIVO' ? 'selected' : ''); ?>>EXPLOSIVO</option>
                            <option value="FORENSE" <?php echo e(old('tipo_busqueda') == 'FORENSE' ? 'selected' : ''); ?>>FORENSE</option>
                            <option value="NARCOTICOS" <?php echo e(old('tipo_busqueda') == 'NARCOTICOS' ? 'selected' : ''); ?>>NARCOTICOS</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Elemento</label>
                        <select name="personal_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $personales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('personal_id') == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->nombres); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Canino</label>
                        <select name="canino_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $caninos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e(old('canino_id') == $c->id ? 'selected' : ''); ?>>
                                    <?php echo e($c->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Equino</label>
                        <select name="equino_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $equinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($e->id); ?>" <?php echo e(old('equino_id') == $e->id ? 'selected' : ''); ?>>
                                    <?php echo e($e->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Patrulla</label>
                        <select name="patrulla_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $patrullas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('patrulla_id') == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->nombre ?? $p->numero ?? $p->placas ?? 'ID '.$p->id); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>¿Se cumplió?</label>
                        <select name="cumplio" class="form-control">
                            <option value="1" <?php echo e(old('cumplio', '1') == '1' ? 'selected' : ''); ?>>Sí</option>
                            <option value="0" <?php echo e(old('cumplio') == '0' ? 'selected' : ''); ?>>No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="3" class="form-control"><?php echo e(old('observaciones')); ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="<?php echo e(url('/servicios')); ?>" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    function actualizarBloquesServicio() {
        let tipoServicio = ($('#tipo_servicio').val() || '').toUpperCase().trim();
        let categoriaRegistro = ($('#categoria_registro').val() || '').toUpperCase().trim();
        let asunto = $('#asunto');

        $('#bloque_busqueda').addClass('d-none');

        if (tipoServicio === 'BUSQUEDA') {
            $('#bloque_busqueda').removeClass('d-none');
        }

        if (!asunto.val().trim()) {
            if (categoriaRegistro && tipoServicio) {
                asunto.val(categoriaRegistro + ' DE ' + tipoServicio);
            } else if (tipoServicio) {
                asunto.val(tipoServicio);
            }
        }
    }

    $('#tipo_servicio, #categoria_registro').on('change', function () {
        actualizarBloquesServicio();
    });

    $(document).ready(function () {
        actualizarBloquesServicio();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicios/create.blade.php ENDPATH**/ ?>
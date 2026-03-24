

<?php $__env->startSection('title', 'Registrar Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Registrar Servicio / Apoyo / Memorándum</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                        <select name="tipo_registro" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SERVICIO">SERVICIO</option>
                            <option value="APOYO">APOYO</option>
                            <option value="MEMORANDUM">MEMORANDUM</option>
                        </select>
                    </div>
                </div>

                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de servicio</label>
                        <select name="tipo_servicio" id="tipo_servicio" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="SEGURIDAD">SEGURIDAD</option>
                            <option value="BARRIDOS DE SEGURIDAD">BARRIDOS DE SEGURIDAD</option>
                            <option value="BUSQUEDA">BUSQUEDA</option>
                            <option value="DESFILES">DESFILES</option>
                            <option value="PROXIMIDAD SOCIAL">PROXIMIDAD SOCIAL</option>
                            <option value="ACTOS CIVICOS">ACTOS CIVICOS</option>
                        </select>
                    </div>
                </div>

                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>

                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time" name="hora" class="form-control" required>
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
                            <option value="EN VIDA">EN VIDA</option>
                            <option value="RECURSO HUMANO">RECURSO HUMANO</option>
                            <option value="EXPLOSIVO">EXPLOSIVO</option>
                            <option value="FORENSE">FORENSE</option>
                            <option value="NARCOTICOS">NARCOTICOS</option>
                        </select>
                    </div>
                </div>

            </div>

            
            <div class="row mt-3">

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Seguridad</label><br>
                        <input type="checkbox" name="seguridad" value="1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Barridos</label><br>
                        <input type="checkbox" name="barridos_seguridad" value="1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Desfiles</label><br>
                        <input type="checkbox" name="desfiles" value="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Proximidad social</label><br>
                        <input type="checkbox" name="proximidad_social" value="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Actos cívicos</label><br>
                        <input type="checkbox" name="actos_civicos" value="1">
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
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->nombres); ?></option>
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
                                <option value="<?php echo e($c->id); ?>"><?php echo e($c->nombre); ?></option>
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
                                <option value="<?php echo e($e->id); ?>"><?php echo e($e->nombre); ?></option>
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
                                <option value="<?php echo e($p->id); ?>">
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
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="<?php echo e(url('/servicios')); ?>" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>

    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $('#tipo_servicio').on('change', function () {
        let val = $(this).val();

        $('#bloque_busqueda').addClass('d-none');

        if (val === 'BUSQUEDA') {
            $('#bloque_busqueda').removeClass('d-none');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicios/create.blade.php ENDPATH**/ ?>
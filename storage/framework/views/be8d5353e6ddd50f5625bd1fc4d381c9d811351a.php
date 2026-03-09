



<?php $__env->startSection('title', 'Subir Documento'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Subir Documento</h1>

    <a href="<?php echo e(route('personal.documentos.index', $personal->id)); ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">Nuevo documento para <?php echo e($personal->nombres); ?></h3>
            </div>

            <form action="<?php echo e(route('personal.documentos.store', $personal->id)); ?>"
                  method="POST"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Título *</label>
                                <input type="text"
                                       name="titulo"
                                       class="form-control"
                                       required
                                       value="<?php echo e(old('titulo')); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <input type="text"
                                       name="tipo_documento"
                                       class="form-control"
                                       value="<?php echo e(old('tipo_documento')); ?>">
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha del documento</label>
                                <input type="date"
                                       name="fecha_documento"
                                       class="form-control"
                                       value="<?php echo e(old('fecha_documento')); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Archivo *</label>
                                <input type="file"
                                       name="archivo"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion"
                                          class="form-control"
                                          rows="3"><?php echo e(old('descripcion')); ?></textarea>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones"
                                          class="form-control"
                                          rows="3"><?php echo e(old('observaciones')); ?></textarea>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="card-footer d-flex justify-content-between">

                    <a href="<?php echo e(route('personal.documentos.index', $personal->id)); ?>"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-upload"></i> Subir documento
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<style>
.form-control{
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

.form-control:focus{
    background-color: #25364a !important;
    color: #ffffff !important;
}
</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

<?php if($errors->any()): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    html: `<?php echo implode('<br>', $errors->all()); ?>`
});
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/documentos/create.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Crear Responsable'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Crear Responsable</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Responsable</h3>
                </div>

                <div class="card-body">

                    <form action="<?php echo e(route('responsables.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Personal</label>
                                    <select name="personal_id" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php $__currentLoopData = $personals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($p->id); ?>"
                                                <?php echo e(old('personal_id') == $p->id ? 'selected' : ''); ?>>
                                                <?php echo e($p->nombres); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nivel</label>
                                    <select name="nivel" id="nivel" class="form-control" required>
                                        <option value="GENERAL" <?php echo e(old('nivel') == 'GENERAL' ? 'selected' : ''); ?>>
                                            GENERAL
                                        </option>
                                        <option value="AREA" <?php echo e(old('nivel') == 'AREA' ? 'selected' : ''); ?>>
                                            ÁREA
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Área</label>
                                    <select name="area_id" id="area_id" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($a->id); ?>"
                                                <?php echo e(old('area_id') == $a->id ? 'selected' : ''); ?>>
                                                <?php echo e($a->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-2">
                            <label>
                                <input type="checkbox" name="activo" value="1"
                                    <?php echo e(old('activo', true) ? 'checked' : ''); ?>>
                                Responsable Activo
                            </label>
                        </div>

                        <hr>

                        <div class="form-group">
                            <a href="<?php echo e(route('responsables.index')); ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Cancelar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Guardar Responsable
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    function toggleArea() {
        let nivel = document.getElementById("nivel").value;
        let areaSelect = document.getElementById("area_id");

        if (nivel === "GENERAL") {
            areaSelect.value = "";
            areaSelect.disabled = true;
        } else {
            areaSelect.disabled = false;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        toggleArea();
        document.getElementById("nivel").addEventListener("change", toggleArea);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/responsables/create.blade.php ENDPATH**/ ?>
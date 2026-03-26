

<?php $__env->startSection('title', 'Gestionar Permisos'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Gestionar Permisos para el Rol: <?php echo e($role->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card card-outline card-warning">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Asignar Permisos</h3>

                <div class="d-flex gap-2" style="gap:8px;">
                    <input type="text" id="permSearch" class="form-control form-control-sm"
                        placeholder="Buscar módulo o permiso..." style="width: 260px;">
                    <button type="button" class="btn btn-sm btn-outline-light" id="checkAll">
                        <i class="fa-solid fa-check-double"></i> Marcar todo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light" id="uncheckAll">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('roles.assignPermissions', $role->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row" id="permCards">
                        <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 perm-card" data-module="<?php echo e(Str::lower($module)); ?>">
                                <div class="card card-outline card-secondary h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <strong class="module-title"><?php echo e($module); ?></strong>

                                        <div class="d-flex" style="gap:6px;">
                                            <button type="button"
                                                class="btn btn-xs btn-outline-primary module-check"
                                                data-target="<?php echo e(Str::slug($module)); ?>">
                                                Marcar
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-outline-secondary module-uncheck"
                                                data-target="<?php echo e(Str::slug($module)); ?>">
                                                Limpiar
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body pt-3">
                                        <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $permName = trim($permission->name);
                                                $parts = preg_split('/\s+/', $permName, 2);
                                                $action = $parts[0] ?? $permName; // ver/crear/editar/eliminar...
                                                $actionLabel = ucfirst($action);
                                                $actionKey = Str::lower($actionLabel);
                                            ?>

                                            <div class="d-flex align-items-center justify-content-between perm-item"
                                                 data-text="<?php echo e(Str::lower($permName)); ?> <?php echo e(Str::lower($module)); ?>">

                                                <div class="text-truncate" style="max-width: 78%;">
                                                    <span class="badge badge-light mr-2"><?php echo e($actionLabel); ?></span>
                                                    <span class="text-muted"><?php echo e($permName); ?></span>
                                                </div>

                                                <label class="circle-check mb-0">
                                                    <input type="checkbox"
                                                        class="perm-checkbox perm-<?php echo e(Str::slug($module)); ?>"
                                                        name="permissions[]"
                                                        value="<?php echo e($permission->id); ?>"
                                                        <?php echo e(in_array($permission->id, $rolePermissions) ? 'checked' : ''); ?>>
                                                    <span class="circle"></span>
                                                </label>
                                            </div>

                                            <hr class="my-2">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="form-group mt-3 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Guardar Permisos
                        </button>
                        <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-ban"></i> Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    /* Cards más compactas */
    .perm-item { padding: 6px 2px; }
    .perm-item .badge { font-size: 12px; }

    /* Checkbox circular (custom) */
    .circle-check { cursor: pointer; display: inline-flex; align-items: center; }
    .circle-check input { display: none; }

    .circle-check .circle{
        width: 22px;
        height: 22px;
        border: 2px solid rgba(255,255,255,.55);
        border-radius: 50%;
        display: inline-block;
        position: relative;
        transition: .15s ease-in-out;
        box-shadow: 0 0 0 2px rgba(0,0,0,.15) inset;
    }

    .circle-check input:checked + .circle{
        border-color: #2ecc71;
        background: rgba(46, 204, 113, .18);
    }

    .circle-check input:checked + .circle::after{
        content: "";
        position: absolute;
        left: 6px;
        top: 2px;
        width: 6px;
        height: 12px;
        border: solid #2ecc71;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }

    /* Buscar: ocultar cards */
    .hidden { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    // Marcar / Limpiar todo
    document.getElementById('checkAll').addEventListener('click', () => {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
    });

    document.getElementById('uncheckAll').addEventListener('click', () => {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
    });

    // Marcar / limpiar por módulo
    document.querySelectorAll('.module-check').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-target');
            document.querySelectorAll('.perm-' + target).forEach(cb => cb.checked = true);
        });
    });

    document.querySelectorAll('.module-uncheck').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-target');
            document.querySelectorAll('.perm-' + target).forEach(cb => cb.checked = false);
        });
    });

    const search = document.getElementById('permSearch');
    search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();

        document.querySelectorAll('.perm-card').forEach(card => {
            const module = (card.getAttribute('data-module') || '');
            const items = Array.from(card.querySelectorAll('.perm-item'));
            const anyMatch = items.some(i => (i.getAttribute('data-text') || '').includes(q)) || module.includes(q);

            card.classList.toggle('hidden', q.length > 0 && !anyMatch);
        });
    });

    <?php if(session('success')): ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '<?php echo e(session('success')); ?>',
            showConfirmButton: false,
            timer: 1500
        });
    <?php endif; ?>

    <?php if($errors->any()): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error en el formulario',
            html: `
                <ul style="text-align: left;">
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/roles/permissions.blade.php ENDPATH**/ ?>
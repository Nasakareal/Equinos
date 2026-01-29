

<?php $__env->startSection('title', 'Detalle del Tipo de Incidencia'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle del Tipo de Incidencia</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">

    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Información del Tipo de Incidencia</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar incidencias')): ?>
                        <a href="<?php echo e(route('incidence_types.edit', $incidence_type->id)); ?>"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Clave</th>
                            <td><?php echo e($incidence_type->clave); ?></td>
                        </tr>

                        <tr>
                            <th>Nombre</th>
                            <td><?php echo e($incidence_type->nombre); ?></td>
                        </tr>

                        <tr>
                            <th>¿Afecta servicio?</th>
                            <td>
                                <?php if((int)$incidence_type->afecta_servicio === 1): ?>
                                    <span class="badge badge-warning">SÍ</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">NO</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Color</th>
                            <td>
                                <?php if(!empty($incidence_type->color)): ?>
                                    <span class="badge"
                                          style="background: <?php echo e($incidence_type->color); ?>; color:#fff;">
                                        <?php echo e($incidence_type->color); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Estatus</th>
                            <td>
                                <?php if((int)$incidence_type->activo === 1): ?>
                                    <span class="badge badge-success">ACTIVO</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">INACTIVO</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de registro</th>
                            <td><?php echo e(optional($incidence_type->created_at)->format('d/m/Y H:i')); ?></td>
                        </tr>

                        <tr>
                            <th>Última actualización</th>
                            <td><?php echo e(optional($incidence_type->updated_at)->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="<?php echo e(route('incidence_types.index')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar incidencias')): ?>
                    <form action="<?php echo e(route('incidence_types.destroy', $incidence_type->id)); ?>"
                          method="POST"
                          class="d-inline-block"
                          id="delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" class="btn btn-danger" id="delete-btn">
                            <i class="fa-regular fa-trash-can"></i> Eliminar
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .table th{
        background-color: rgba(255,255,255,.08);
        color: #ffffff;
        white-space: nowrap;
    }

    .table td{
        color: #ffffff;
        vertical-align: middle;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).on('click', '#delete-btn', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Eliminar este tipo de incidencia?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    });

    <?php if(session('success')): ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '<?php echo e(session('success')); ?>',
            showConfirmButton: false,
            timer: 12000
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/incidence_types/show.blade.php ENDPATH**/ ?>
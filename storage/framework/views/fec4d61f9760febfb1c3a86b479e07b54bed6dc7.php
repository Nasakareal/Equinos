

<?php $__env->startSection('title', 'Detalle de Incidencia'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle de Incidencia</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Incidencia #<?php echo e($incidence->id); ?>

                </h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar incidencias')): ?>
                        <a href="<?php echo e(route('incidencias.edit', $incidence->id)); ?>"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('incidencias.index')); ?>"
                       class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <!-- Personal -->
                    <div class="col-md-6">
                        <strong>Personal:</strong>
                        <p class="mb-2">
                            <?php echo e($incidence->personal->nombres ?? '—'); ?>

                        </p>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-6">
                        <strong>Tipo de incidencia:</strong>
                        <p class="mb-2">
                            <?php echo e($incidence->type->nombre ?? '—'); ?>

                        </p>
                    </div>

                </div>

                <div class="row">

                    <!-- Fecha inicio -->
                    <div class="col-md-4">
                        <strong>Fecha inicio:</strong>
                        <p class="mb-2">
                            <?php echo e(optional($incidence->fecha_inicio)->format('d/m/Y')); ?>

                        </p>
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-4">
                        <strong>Fecha fin:</strong>
                        <p class="mb-2">
                            <?php if(!empty($incidence->fecha_fin)): ?>
                                <?php echo e(optional($incidence->fecha_fin)->format('d/m/Y')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Registrado por -->
                    <div class="col-md-4">
                        <strong>Registrado por:</strong>
                        <p class="mb-2">
                            <?php if(!empty($incidence->registrado_por)): ?>
                                Usuario #<?php echo e($incidence->registrado_por); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </p>
                    </div>

                </div>

                <hr>

                <!-- Comentario -->
                <div class="row">
                    <div class="col-md-12">
                        <strong>Comentario:</strong>
                        <div class="border rounded p-3 mt-2" style="background:#f8f9fa;">
                            <?php echo e($incidence->comentario ?? 'Sin comentarios.'); ?>

                        </div>
                    </div>
                </div>

            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar incidencias')): ?>
            <div class="card-footer text-right">
                <form action="<?php echo e(route('incidencias.destroy', $incidence->id)); ?>"
                      method="POST"
                      style="display:inline-block;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button"
                            class="btn btn-danger delete-btn">
                        <i class="fa-regular fa-trash-can"></i> Eliminar incidencia
                    </button>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Eliminar incidencia?',
            text: 'Esta acción no se puede revertir',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    <?php if(session('success')): ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '<?php echo e(session('success')); ?>',
            showConfirmButton: false,
            timer: 10000
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/incidencias/show.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Incidencias'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Incidencias</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Listado de Incidencias</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear incidencias')): ?>
                        <a href="<?php echo e(route('incidencias.create')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Nueva Incidencia
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="incidencias" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Personal</th>
                                <th>Tipo</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Comentario</th>
                                <th>Registró</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $incidencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>

                                    <td style="white-space: normal; min-width: 240px;">
                                        <?php echo e($inc->personal->nombres ?? '—'); ?>

                                    </td>

                                    <td><?php echo e($inc->type->nombre ?? '—'); ?></td>

                                    <td><?php echo e(optional($inc->fecha_inicio)->format('d/m/Y')); ?></td>

                                    <td>
                                        <?php if(!empty($inc->fecha_fin)): ?>
                                            <?php echo e(optional($inc->fecha_fin)->format('d/m/Y')); ?>

                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>

                                    <td style="white-space: normal; min-width: 320px; text-align:left;">
                                        <?php echo e($inc->comentario ?? '-'); ?>

                                    </td>

                                    <td>
                                        <?php if(!empty($inc->registrado_por)): ?>
                                            <?php echo e($inc->registrado_por); ?>

                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver incidencias')): ?>
                                                <a href="<?php echo e(route('incidencias.show', $inc->id)); ?>"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar incidencias')): ?>
                                                <a href="<?php echo e(route('incidencias.edit', $inc->id)); ?>"
                                                   class="btn btn-success btn-sm"
                                                   title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar incidencias')): ?>
                                                <form action="<?php echo e(route('incidencias.destroy', $inc->id)); ?>"
                                                      method="POST"
                                                      style="display:inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn"
                                                            title="Eliminar">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .table th, .table td{
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function () {
        const dt = $('#incidencias').DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay información",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ total registros)",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscador:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            scrollX: true,
            deferRender: true
        });

        setTimeout(function () {
            dt.columns.adjust().responsive.recalc();
        }, 150);
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

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Eliminar incidencia?',
            text: "Esta acción no se puede revertir",
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/incidencias/index.blade.php ENDPATH**/ ?>
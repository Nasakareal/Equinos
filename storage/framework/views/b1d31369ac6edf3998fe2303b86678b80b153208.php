

<?php $__env->startSection('title', 'Asignaciones de Armamento'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Asignaciones de Armamento</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Historial de Asignaciones</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear armamento')): ?>
                        <a href="<?php echo e(route('armamento_asignaciones.create')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Nueva Asignación
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="weapon_assignments" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Arma</th>
                                <th>Matrícula</th>
                                <th>Tipo</th>
                                <th>Personal</th>
                                <th>Fecha asignación</th>
                                <th>Fecha devolución</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $weapon_assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $wa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>

                                    <td>
                                        <?php echo e($wa->weapon->marca_modelo ?? '—'); ?>

                                    </td>

                                    <td>
                                        <?php echo e($wa->weapon->matricula ?? '—'); ?>

                                    </td>

                                    <td>
                                        <?php echo e($wa->weapon->tipo ?? '—'); ?>

                                    </td>

                                    <td>
                                        <?php echo e($wa->personal->nombres ?? '—'); ?>

                                    </td>

                                    <td>
                                        <?php echo e(optional($wa->fecha_asignacion)->format('d/m/Y') ?? '-'); ?>

                                    </td>

                                    <td>
                                        <?php echo e(optional($wa->fecha_devolucion)->format('d/m/Y') ?? '-'); ?>

                                    </td>

                                    <td>
                                        <?php if($wa->status === 'ASIGNADA'): ?>
                                            <span class="badge badge-success">ASIGNADA</span>
                                        <?php elseif($wa->status === 'DEVUELTA'): ?>
                                            <span class="badge badge-secondary">DEVUELTA</span>
                                        <?php elseif($wa->status === 'CANCELADA'): ?>
                                            <span class="badge badge-danger">CANCELADA</span>
                                        <?php else: ?>
                                            <span class="badge badge-dark"><?php echo e($wa->status); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver armamento')): ?>
                                                <a href="<?php echo e(route('armamento_asignaciones.show', $wa->id)); ?>"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar armamento')): ?>
                                                <a href="<?php echo e(route('armamento_asignaciones.edit', $wa->id)); ?>"
                                                   class="btn btn-success btn-sm"
                                                   title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar armamento')): ?>
                                                <form action="<?php echo e(route('armamento_asignaciones.destroy', $wa->id)); ?>"
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
        const dt = $('#weapon_assignments').DataTable({
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
            title: '¿Eliminar asignación?',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/armamento_asignaciones/index.blade.php ENDPATH**/ ?>
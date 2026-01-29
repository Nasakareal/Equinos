



<?php $__env->startSection('title', 'Horarios de Turnos'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Horarios de Turnos</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Listado de Horarios</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear turnos')): ?>
                        <a href="<?php echo e(route('turno_horarios.create')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Agregar Horario
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="turno_horarios" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th><center>#</center></th>
                                <th><center>Turno</center></th>
                                <th><center>Entrada</center></th>
                                <th><center>Salida</center></th>
                                <th><center>Tolerancia (min)</center></th>
                                <th><center>Cruza día</center></th>
                                <th><center>Notas</center></th>
                                <th><center>Acciones</center></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $turno_horarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $horario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>

                                    <td>
                                        <?php echo e($horario->turno->nombre ?? '-'); ?>

                                        <br>
                                        <small class="text-muted">
                                            <?php echo e($horario->turno->clave ?? ''); ?>

                                        </small>
                                    </td>

                                    <td><?php echo e($horario->hora_entrada ?? '-'); ?></td>
                                    <td><?php echo e($horario->hora_salida ?? '-'); ?></td>
                                    <td><?php echo e($horario->min_tolerancia); ?></td>

                                    <td>
                                        <?php if((int)$horario->cruza_dia === 1): ?>
                                            <span class="badge badge-warning">Sí</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">No</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e($horario->notas ?? '-'); ?></td>

                                    <td>
                                        <div class="btn-group" role="group">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver turnos')): ?>
                                                <a href="<?php echo e(route('turno_horarios.show', $horario->id)); ?>"
                                                   class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar turnos')): ?>
                                                <a href="<?php echo e(route('turno_horarios.edit', $horario->id)); ?>"
                                                   class="btn btn-success btn-sm" title="Editar">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar turnos')): ?>
                                                <form action="<?php echo e(route('turno_horarios.destroy', $horario->id)); ?>"
                                                      method="POST" style="display:inline-block;">
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
    .dataTables_wrapper{ width: 100%; }
    table.dataTable{ width: 100% !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function () {
        const dt = $('#turno_horarios').DataTable({
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

        setTimeout(() => {
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
            title: '¿Eliminar este horario?',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/turno_horarios/index.blade.php ENDPATH**/ ?>
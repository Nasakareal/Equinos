



<?php $__env->startSection('title', 'Listado de Armamento'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Listado de Armamento</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Armamento Registrado</h3>

                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear armamento')): ?>
                            <a href="<?php echo e(route('armamento.create')); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Agregar Armamento
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="weapons" class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Tipo</center></th>
                                    <th><center>Marca / Modelo</center></th>
                                    <th><center>Matrícula</center></th>
                                    <th><center>Estado</center></th>
                                    <th><center>Observaciones</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $weapons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $weapon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($weapon->tipo ?? '-'); ?></td>
                                        <td><?php echo e($weapon->marca_modelo ?? '-'); ?></td>
                                        <td><?php echo e($weapon->matricula ?? '-'); ?></td>
                                        <td>
                                            <?php
                                                $estado = strtoupper((string)($weapon->estado ?? ''));
                                            ?>

                                            <?php if($estado === 'ACTIVA'): ?>
                                                <span class="badge badge-success">ACTIVA</span>
                                            <?php elseif($estado === 'INACTIVA'): ?>
                                                <span class="badge badge-secondary">INACTIVA</span>
                                            <?php elseif($estado === 'BAJA'): ?>
                                                <span class="badge badge-danger">BAJA</span>
                                            <?php else: ?>
                                                <span class="badge badge-dark"><?php echo e($weapon->estado ?? '-'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="white-space: normal; min-width: 240px;">
                                            <?php echo e($weapon->observaciones ?? '-'); ?>

                                        </td>

                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver armamento')): ?>
                                                    <a href="<?php echo e(route('armamento.show', $weapon->id)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar armamento')): ?>
                                                    <a href="<?php echo e(route('armamento.edit', $weapon->id)); ?>" class="btn btn-success btn-sm" title="Editar">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar armamento')): ?>
                                                    <form action="<?php echo e(route('armamento.destroy', $weapon->id)); ?>" method="POST" style="display:inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" title="Eliminar">
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

        .dataTables_wrapper{
            width: 100%;
        }

        table.dataTable{
            width: 100% !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(function () {
            const dt = $('#weapons').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ total registros)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "scrollX": true,
                "deferRender": true
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
                title: '¿Estás seguro de eliminar este registro?',
                text: "¡No podrás revertir esta acción!",
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/armamento/index.blade.php ENDPATH**/ ?>
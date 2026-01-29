



<?php $__env->startSection('title', 'Listado de Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Listado de Personal</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Personal Registrado</h3>
                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear personal')): ?>
                            <a href="<?php echo e(url('/personal/create')); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Agregar Personal
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table id="personals" class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th><center>Número</center></th>
                                    <th><center>Grado</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Dependencia</center></th>
                                    <th><center>CUIP</center></th>
                                    <th><center>Celular</center></th>
                                    <th><center>CRP</center></th>
                                    <th><center>Cargo</center></th>
                                    <th><center>Responsable</center></th>
                                    <th><center>Estado</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $personals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $personal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($personal->grado ?? '-'); ?></td>
                                        <td><?php echo e($personal->nombres); ?></td>
                                        <td><?php echo e($personal->dependencia ?? '-'); ?></td>
                                        <td><?php echo e($personal->cuip ?? '-'); ?></td>
                                        <td><?php echo e($personal->celular ?? '-'); ?></td>
                                        <td><?php echo e($personal->crp ?? '-'); ?></td>
                                        <td><?php echo e($personal->cargo ?? '-'); ?></td>
                                        <td>
                                            <?php if(!empty($personal->es_responsable) && (int)$personal->es_responsable === 1): ?>
                                                <span class="badge badge-success">Sí</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(!empty($personal->activo) && (int)$personal->activo === 1): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver personal')): ?>
                                                    <a href="<?php echo e(url('/personal/' . $personal->id)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                                                    <a href="<?php echo e(url('/personal/' . $personal->id . '/edit')); ?>" class="btn btn-success btn-sm" title="Editar">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar personal')): ?>
                                                    <form action="<?php echo e(url('/personal/' . $personal->id)); ?>" method="POST" style="display:inline-block;">
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
            white-space: nowrap; /* ✅ evita que crezcan filas y “rompa” el layout */
        }

        /* ✅ DataTables dentro de cards: fuerza a no desbordar el contenedor */
        .dataTables_wrapper{
            width: 100%;
        }

        /* ✅ evita el “brinco” de ancho al cargar */
        table.dataTable{
            width: 100% !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(function () {
            const dt = $('#personals').DataTable({
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
                "scrollX": true,   // ✅ clave: scroll horizontal para que no se salga del card
                "deferRender": true
            });

            // ✅ cuando AdminLTE/Bootstrap recalcula tamaños, ajusta columnas
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/index.blade.php ENDPATH**/ ?>
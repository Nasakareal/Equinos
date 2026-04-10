

<?php $__env->startSection('title', 'Listado de Servicios'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Servicios, Apoyos y Memorándums</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Control operativo del Agrupamiento de Equinos y Caninos
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear servicios')): ?>
                <a href="<?php echo e(url('/servicios/create')); ?>" class="btn btn-primary shadow-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Registrar Servicio
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $fechaSeleccionada = $fecha;

    $totalServicios = $servicios->count();
    $cumplidos = $servicios->where('cumplio', true)->count();
    $noCumplidos = $servicios->where('cumplio', false)->count();

    $soloServicios = $servicios->where('categoria_registro', 'SERVICIO')->count();
    $soloApoyos = $servicios->where('categoria_registro', 'APOYO')->count();
    $soloMemorandums = $servicios->where('categoria_registro', 'MEMORANDUM')->count();

    function badgeTipoServicio($tipo) {
        return match (strtoupper((string)$tipo)) {
            'SEGURIDAD' => 'badge badge-info',
            'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
            'BUSQUEDA' => 'badge badge-danger',
            'DESFILES' => 'badge badge-purple',
            'PROXIMIDAD SOCIAL' => 'badge badge-success',
            'ACTOS CIVICOS' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };
    }
?>

<div class="card mb-3">
    <div class="card-body d-flex align-items-center" style="gap:10px;">
        <form method="GET" action="<?php echo e(url('/servicios')); ?>" class="d-flex" style="gap:10px;">
            <input type="date" name="fecha" value="<?php echo e($fechaSeleccionada); ?>" class="form-control">
            <button class="btn btn-primary">Filtrar</button>
        </form>

        <a href="<?php echo e(url('/servicios?fecha=' . now()->toDateString())); ?>" class="btn btn-secondary">
            Hoy
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-primary shadow-sm">
            <div class="inner">
                <h3><?php echo e($totalServicios); ?></h3>
                <p>Total del día</p>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-success shadow-sm">
            <div class="inner">
                <h3><?php echo e($cumplidos); ?></h3>
                <p>Cumplidos</p>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-danger shadow-sm">
            <div class="inner">
                <h3><?php echo e($noCumplidos); ?></h3>
                <p>No cumplidos</p>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-indigo shadow-sm">
            <div class="inner">
                <h3><?php echo e($soloServicios); ?></h3>
                <p>Servicios</p>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-teal shadow-sm">
            <div class="inner">
                <h3><?php echo e($soloApoyos); ?></h3>
                <p>Apoyos</p>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="small-box bg-warning shadow-sm">
            <div class="inner">
                <h3><?php echo e($soloMemorandums); ?></h3>
                <p>Memorándums</p>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Listado</h3>

        <div class="card-tools d-flex flex-wrap align-items-center" style="gap: .5rem;">
            <button type="button" class="btn btn-outline-secondary btn-sm filtro-registro" data-tipo="">
                Todos
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm filtro-registro" data-tipo="SERVICIO">
                Servicios
            </button>
            <button type="button" class="btn btn-outline-success btn-sm filtro-registro" data-tipo="APOYO">
                Apoyos
            </button>
            <button type="button" class="btn btn-outline-warning btn-sm filtro-registro" data-tipo="MEMORANDUM">
                Memorándums
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="servicios" class="table table-striped table-bordered table-hover table-sm w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Cumplió</th>
                        <th>Personal</th>
                        <th>Canino</th>
                        <th>Equino</th>
                        <th>Patrulla</th>
                        <th>Creó</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $servicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>

                            <td>
                                <span class="<?php echo e(badgeTipoServicio($servicio->tipo_servicio)); ?> badge-pill px-3 py-2">
                                    <?php echo e($servicio->tipo_servicio); ?>

                                </span>
                            </td>

                            <td><?php echo e(\Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y')); ?></td>
                            <td><?php echo e($servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-'); ?></td>

                            <td>
                                <?php if($servicio->cumplio): ?>
                                    <span class="badge badge-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">No</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo e($servicio->personal->nombres ?? '-'); ?></td>
                            <td><?php echo e($servicio->canino->nombre ?? '-'); ?></td>
                            <td><?php echo e($servicio->equino->nombre ?? '-'); ?></td>
                            <td><?php echo e($servicio->patrulla->nombre ?? '-'); ?></td>
                            <td><?php echo e($servicio->user->name ?? '-'); ?></td>

                            <td>
                                <a href="<?php echo e(url('/servicios/' . $servicio->id)); ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="<?php echo e(url('/servicios/' . $servicio->id . '/edit')); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function () {
        let tabla = $('#servicios').DataTable({
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
            deferRender: true,
            order: [[2, 'desc'], [3, 'desc']]
        });

        setTimeout(function () {
            tabla.columns.adjust().responsive.recalc();
        }, 150);

        $('.filtro-registro').on('click', function () {
            let tipo = $(this).data('tipo');
            $('.filtro-registro').removeClass('active-filter');
            $(this).addClass('active-filter');
            tabla.search(tipo).draw();
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

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');

        Swal.fire({
            title: '¿Estás seguro de eliminar este registro?',
            text: '¡No podrás revertir esta acción!',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicios/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Puestas a Disposición'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Puestas a Disposición</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-danger">

            <div class="card-header">
                <h3 class="card-title">Listado de Puestas a Disposición</h3>

                <div class="card-tools d-flex align-items-center" style="gap:8px;">

                    <form method="GET" class="d-inline-block">
                        <input type="text"
                               name="buscar"
                               value="<?php echo e(request('buscar')); ?>"
                               class="form-control form-control-sm d-inline-block"
                               style="width:220px"
                               placeholder="Buscar folio u observaciones">
                    </form>

                    <form method="GET" class="d-inline-block">
                        <input type="number"
                               name="anio"
                               value="<?php echo e(request('anio')); ?>"
                               class="form-control form-control-sm d-inline-block"
                               style="width:120px"
                               placeholder="Año">
                    </form>

                    <form method="GET" class="d-inline-block">
                        <select name="personal_id" class="form-control form-control-sm d-inline-block" style="width:260px">
                            <option value="">Todo el personal</option>
                            <?php $__currentLoopData = $personals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e((string)request('personal_id')===(string)$p->id?'selected':''); ?>>
                                    <?php echo e(trim(($p->grado ?? '').' '.($p->nombres ?? ''))); ?><?php echo e($p->cargo ? ' · '.$p->cargo : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>

                    <a href="<?php echo e(route('puestas_disposicion.index')); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear puestas_disposicion')): ?>
                        <a href="<?php echo e(route('puestas_disposicion.create')); ?>" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-plus"></i> Registrar
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="puestas" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Folio</th>
                                <th>Año</th>
                                <th>Personal</th>
                                <th>PDF</th>
                                <th>Observaciones</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $puestas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>

                                    <td>
                                        <span class="badge badge-danger" style="font-size: 0.95rem;">
                                            <?php echo e($pd->folio); ?>

                                        </span>
                                    </td>

                                    <td><?php echo e($pd->anio); ?></td>

                                    <td>
                                        <?php echo e(trim(($pd->personal->grado ?? '').' '.($pd->personal->nombres ?? '')) ?: '-'); ?>

                                        <?php if(!empty($pd->personal->cargo)): ?>
                                            <div class="text-muted small"><?php echo e($pd->personal->cargo); ?></div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if(!empty($pd->archivo_pdf)): ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($pd->archivo_pdf)); ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="fa-solid fa-file-pdf"></i> Ver
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Sin archivo</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="text-align:left;">
                                        <?php echo e($pd->observaciones ?? '-'); ?>

                                    </td>

                                    <td>
                                        <?php echo e($pd->created_at ? $pd->created_at->format('d/m/Y H:i') : '-'); ?>

                                    </td>

                                    <td>
                                        <div class="btn-group">

                                            <a href="<?php echo e(route('puestas_disposicion.show', $pd->id)); ?>" class="btn btn-info btn-sm">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar puestas_disposicion')): ?>
                                                <a href="<?php echo e(route('puestas_disposicion.edit', $pd->id)); ?>" class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar puestas_disposicion')): ?>
                                                <form action="<?php echo e(route('puestas_disposicion.destroy', $pd->id)); ?>" method="POST" style="display:inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
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

            <?php if(method_exists($puestas, 'links')): ?>
                <div class="card-footer">
                    <?php echo e($puestas->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<style>

input.form-control,
textarea.form-control,
select.form-control {
    background-color: #1f2d3d !important;
    color: #ffffff !important;
    border: 1px solid #3c4b64 !important;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    background-color: #25364a !important;
    color: #ffffff !important;
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25) !important;
}

select.form-control option {
    background-color: #ffffff !important;
    color: #000000 !important;
}

::placeholder {
    color: #b8c7ce !important;
    opacity: 1;
}

label {
    color: #d2d6de;
    font-weight: 600;
}

.btn-purple {
    background: linear-gradient(135deg, #6f42c1, #4e2a8e) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 8px 18px;
    box-shadow: 0 4px 10px rgba(111, 66, 193, 0.35);
    transition: all 0.25s ease-in-out;
}

.btn-purple:hover {
    background: linear-gradient(135deg, #5a32a3, #3d1f73) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(111, 66, 193, 0.45);
}

.btn-purple:focus,
.btn-purple:active {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.4) !important;
}

</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

<script>
$(function () {

    $('#puestas').DataTable({
        "pageLength": 10,
        "language": {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(Filtrado de _MAX_ total registros)",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "responsive": true,
        "autoWidth": false,
        "scrollX": true
    });

});


<?php if(session('success')): ?>
Swal.fire({
    position: 'center',
    icon: 'success',
    title: '<?php echo e(session('success')); ?>',
    showConfirmButton: false,
    timer: 3000
});
<?php endif; ?>


$(document).on('click', '.delete-btn', function (e) {

    e.preventDefault();
    let form = $(this).closest('form');

    Swal.fire({
        title: '¿Eliminar registro?',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/puestas_disposicion/index.blade.php ENDPATH**/ ?>
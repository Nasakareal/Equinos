

<?php $__env->startSection('title', 'Unidad Canina y Equina'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Unidad Canina y Equina</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-purple">

            <div class="card-header">
                <h3 class="card-title">Listado de Animales</h3>

                <div class="card-tools">

                    <form method="GET" class="d-inline-block mr-2">
                        <select name="tipo" class="form-control form-control-sm d-inline-block" style="width:140px">
                            <option value="">Todos</option>
                            <option value="EQUINO" <?php echo e(request('tipo')=='EQUINO'?'selected':''); ?>>Equinos</option>
                            <option value="CANINO" <?php echo e(request('tipo')=='CANINO'?'selected':''); ?>>Caninos</option>
                        </select>
                    </form>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear animales')): ?>
                        <a href="<?php echo e(url('/animales/create')); ?>" class="btn btn-purple btn-sm">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="animals" class="table table-striped table-bordered table-hover table-sm w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th>Especialidad</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $animals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $animal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <?php if($animal->tipo == 'EQUINO'): ?>
                                            <span class="badge badge-info">Equino</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Canino</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($animal->nombre); ?></td>
                                    <td><?php echo e($animal->raza ?? '-'); ?></td>
                                    <td><?php echo e($animal->especialidad ?? '-'); ?></td>
                                    <td>
                                        <?php if($animal->estatus == 'ACTIVO'): ?>
                                            <span class="badge badge-success">Activo</span>
                                        <?php elseif($animal->estatus == 'BAJA'): ?>
                                            <span class="badge badge-danger">Baja</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Resguardo</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="btn-group">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver animales')): ?>
                                                <a href="<?php echo e(url('/animales/'.$animal->id)); ?>"
                                                   class="btn btn-info btn-sm">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar animales')): ?>
                                                <a href="<?php echo e(url('/animales/'.$animal->id.'/edit')); ?>"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar animales')): ?>
                                                <form action="<?php echo e(url('/animales/'.$animal->id)); ?>"
                                                      method="POST"
                                                      style="display:inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn">
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

/* Centrar contenido de tabla */
.table th, .table td{
    text-align:center;
    vertical-align:middle;
}

/* Botón Agregar más visible */
.btn-purple{
    background: linear-gradient(135deg, #6f42c1, #4e2a8e);
    border: none;
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(111, 66, 193, 0.35);
    transition: all 0.3s ease-in-out;
}

.btn-purple:hover{
    background: linear-gradient(135deg, #5a32a3, #3d1f73);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(111, 66, 193, 0.45);
}

.btn-purple:focus,
.btn-purple:active{
    outline: none;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.4);
}

</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

<script>
$(function () {

    $('#animals').DataTable({
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/animales/index.blade.php ENDPATH**/ ?>
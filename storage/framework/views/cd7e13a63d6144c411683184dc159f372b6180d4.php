



<?php $__env->startSection('title', 'Documentos del Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Documentos de <?php echo e($personal->nombres); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Listado de Documentos</h3>

                    <div class="card-tools">
                        <a href="<?php echo e(route('personal.show', $personal->id)); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver al personal
                        </a>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                            <a href="<?php echo e(route('personal.documentos.create', $personal->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-upload"></i> Subir documento
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">

                    <form action="<?php echo e(route('personal.documentos.index', $personal->id)); ?>" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text"
                                       name="buscar"
                                       class="form-control"
                                       placeholder="Buscar por título, tipo, descripción, nombre del archivo u observaciones..."
                                       value="<?php echo e($buscar ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php if(isset($documentos) && $documentos->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th><center>#</center></th>
                                        <th><center>Título</center></th>
                                        <th><center>Tipo</center></th>
                                        <th><center>Archivo</center></th>
                                        <th><center>Fecha documento</center></th>
                                        <th><center>Tamaño</center></th>
                                        <th><center>Estatus</center></th>
                                        <th><center>Acciones</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $documento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="text-align: center">
                                                <?php echo e($documentos->firstItem() + $index); ?>

                                            </td>
                                            <td><?php echo e($documento->titulo); ?></td>
                                            <td><?php echo e($documento->tipo_documento ?? '-'); ?></td>
                                            <td><?php echo e($documento->nombre_original ?? '-'); ?></td>
                                            <td style="text-align: center">
                                                <?php if($documento->fecha_documento): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y')); ?>

                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                    $tamano = (int)($documento->tamano ?? 0);
                                                    if ($tamano >= 1048576) {
                                                        $tamanoTxt = number_format($tamano / 1048576, 2) . ' MB';
                                                    } elseif ($tamano >= 1024) {
                                                        $tamanoTxt = number_format($tamano / 1024, 2) . ' KB';
                                                    } elseif ($tamano > 0) {
                                                        $tamanoTxt = $tamano . ' B';
                                                    } else {
                                                        $tamanoTxt = '-';
                                                    }
                                                ?>
                                                <?php echo e($tamanoTxt); ?>

                                            </td>
                                            <td style="text-align: center">
                                                <?php if($documento->activo): ?>
                                                    <span class="badge badge-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="btn-group" role="group">

                                                    <a href="<?php echo e(route('personal.documentos.download', [$personal->id, $documento->id])); ?>"
                                                       class="btn btn-info btn-sm"
                                                       title="Descargar">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                                                        <a href="<?php echo e(route('personal.documentos.edit', [$personal->id, $documento->id])); ?>"
                                                           class="btn btn-success btn-sm"
                                                           title="Editar">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                                                        <form action="<?php echo e(route('personal.documentos.destroy', [$personal->id, $documento->id])); ?>"
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

                                        <?php if(!empty($documento->descripcion) || !empty($documento->observaciones)): ?>
                                            <tr>
                                                <td colspan="8" style="background: rgba(0,0,0,.03);">
                                                    <?php if(!empty($documento->descripcion)): ?>
                                                        <strong>Descripción:</strong> <?php echo e($documento->descripcion); ?><br>
                                                    <?php endif; ?>

                                                    <?php if(!empty($documento->observaciones)): ?>
                                                        <strong>Observaciones:</strong> <?php echo e($documento->observaciones); ?>

                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo e($documentos->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary mb-0">
                            No hay documentos registrados para este elemento de personal.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .table th, .table td{
        text-align:center;
        vertical-align:middle;
    }

    .pagination{
        justify-content:center;
    }

    .pagination .page-item .page-link{
        background: rgba(0,0,0,.22) !important;
        color: rgba(234,240,255,.92) !important;
        border: 1px solid rgba(255,255,255,.14) !important;
        border-radius: 12px !important;
        margin: 0 4px !important;
        padding: 10px 14px !important;
        font-weight: 900 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .pagination .page-item .page-link:hover{
        background: rgba(45,168,255,.18) !important;
        border-color: rgba(45,168,255,.45) !important;
        color: rgba(234,240,255,.98) !important;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link{
        background: linear-gradient(135deg, rgba(45,168,255,.35), rgba(124,92,255,.30)) !important;
        border-color: rgba(45,168,255,.60) !important;
        color: rgba(234,240,255,.98) !important;
    }

    .pagination .page-item.disabled .page-link{
        background: rgba(0,0,0,.14) !important;
        border-color: rgba(255,255,255,.10) !important;
        color: rgba(234,240,255,.55) !important;
        opacity: .55 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .pagination .page-item .page-link:focus{
        box-shadow: 0 0 0 3px rgba(45,168,255,.18) !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
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
            title: '¿Estás seguro de eliminar este documento?',
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/documentos/index.blade.php ENDPATH**/ ?>
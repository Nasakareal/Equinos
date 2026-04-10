

<?php $__env->startSection('title', 'Detalle del Servicio'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1">Detalle del Servicio / Apoyo / Memorándum</h1>
            <div class="text-muted" style="font-size: 0.95rem;">
                Información completa del registro operativo
            </div>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="<?php echo e(url('/servicios')); ?>" class="btn btn-secondary shadow-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver
            </a>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar servicios')): ?>
                <a href="<?php echo e(url('/servicios/' . $servicio->id . '/edit')); ?>" class="btn btn-success shadow-sm">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Editar
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $tipoRegistroClass = match (strtoupper((string) $servicio->categoria_registro)) {
            'SERVICIO' => 'badge badge-primary',
            'APOYO' => 'badge badge-success',
            'MEMORANDUM' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };

        $tipoServicioClass = match (strtoupper((string) $servicio->tipo_servicio)) {
            'SEGURIDAD' => 'badge badge-info',
            'BARRIDOS DE SEGURIDAD' => 'badge badge-dark',
            'BUSQUEDA' => 'badge badge-danger',
            'DESFILES' => 'badge badge-purple',
            'PROXIMIDAD SOCIAL' => 'badge badge-success',
            'ACTOS CIVICOS' => 'badge badge-warning',
            'OTRO' => 'badge badge-secondary',
            default => 'badge badge-secondary',
        };

        $patrullaTexto = '-';
        if (!empty($servicio->patrulla)) {
            $patrullaTexto =
                $servicio->patrulla->nombre ??
                $servicio->patrulla->numero ??
                $servicio->patrulla->placas ??
                ('ID ' . $servicio->patrulla->id);
        }

        $creadoPor = $servicio->creador->name ?? $servicio->creador->nombre ?? '-';
    ?>

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>#<?php echo e($servicio->id); ?></h3>
                    <p>ID del registro</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box <?php echo e($servicio->cumplio ? 'bg-success' : 'bg-danger'); ?> shadow-sm">
                <div class="inner">
                    <h3><?php echo e($servicio->cumplio ? 'Sí' : 'No'); ?></h3>
                    <p>Cumplimiento</p>
                </div>
                <div class="icon">
                    <i class="fa-solid <?php echo e($servicio->cumplio ? 'fa-circle-check' : 'fa-circle-xmark'); ?>"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-indigo shadow-sm">
                <div class="inner">
                    <h3><?php echo e($servicio->fecha ? \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') : '-'); ?></h3>
                    <p>Fecha</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-secondary shadow-sm">
                <div class="inner">
                    <h3><?php echo e($servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-'); ?></h3>
                    <p>Hora</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Información general</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de registro</div>
                        <div class="info-value">
                            <span class="<?php echo e($tipoRegistroClass); ?> badge-pill px-3 py-2">
                                <?php echo e($servicio->categoria_registro ?? '-'); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Tipo de servicio</div>
                        <div class="info-value">
                            <span class="<?php echo e($tipoServicioClass); ?> badge-pill px-3 py-2">
                                <?php echo e($servicio->tipo_servicio ?? '-'); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Número de referencia</div>
                        <div class="info-value">
                            <?php echo e($servicio->folio_referencia ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Creado por</div>
                        <div class="info-value">
                            <?php echo e($creadoPor); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-card">
                        <div class="info-label">Municipio</div>
                        <div class="info-value">
                            <?php echo e($servicio->municipio ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-3">
                    <div class="info-card">
                        <div class="info-label">Lugar</div>
                        <div class="info-value">
                            <?php echo e($servicio->lugar ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="info-card">
                        <div class="info-label">Asunto base</div>
                        <div class="info-value">
                            <?php echo e($servicio->asunto ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <?php if(!empty($servicio->tipo_busqueda)): ?>
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="info-label">Tipo de búsqueda</div>
                            <div class="info-value">
                                <?php echo e($servicio->tipo_busqueda); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card card-outline card-info shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Asignación operativa</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Elemento</div>
                        <div class="info-value">
                            <?php echo e($servicio->personal->nombres ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Canino</div>
                        <div class="info-value">
                            <?php echo e($servicio->canino->nombre ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Equino</div>
                        <div class="info-value">
                            <?php echo e($servicio->equino->nombre ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="info-card">
                        <div class="info-label">Patrulla</div>
                        <div class="info-value">
                            <?php echo e($patrullaTexto); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-dark shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Observaciones</h3>
        </div>

        <div class="card-body">
            <div class="observaciones-box">
                <?php echo e($servicio->observaciones ?: 'Sin observaciones registradas.'); ?>

            </div>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar servicios')): ?>
        <div class="text-right mb-3">
            <form action="<?php echo e(url('/servicios/' . $servicio->id)); ?>" method="POST" class="d-inline-block" id="deleteFormServicio">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="button" class="btn btn-danger shadow-sm" id="btnEliminarServicio">
                    <i class="fa-regular fa-trash-can mr-1"></i> Eliminar registro
                </button>
            </form>
        </div>
    <?php endif; ?>
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

    $(document).on('click', '#btnEliminarServicio', function (e) {
        e.preventDefault();

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
                $('#deleteFormServicio').submit();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/servicios/show.blade.php ENDPATH**/ ?>
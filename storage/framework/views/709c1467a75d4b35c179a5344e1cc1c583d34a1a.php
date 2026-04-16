

<?php $__env->startSection('title', 'Reportes Diarios'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Reportes Diarios</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Consultas por tipo de reporte</h3>
            </div>

            <div class="card-body">

                <?php if(session('success')): ?>
                    <div class="alert alert-success mb-2"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger mb-2"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <div class="row">

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Estado de Fuerza</h3>
                            </div>
                            <div class="card-body">
                                <a href="<?php echo e(route('daily_reports.estado_fuerza.index')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Personal</h3>
                            </div>
                            <div class="card-body">
                                <a href="<?php echo e(route('daily_reports.lista_personal.index')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Pase de Lista Canina</h3>
                            </div>
                            <div class="card-body">
                                <a href="<?php echo e(route('daily_reports.pase_lista_canina.index')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Pase de Lista Agrupamiento Equinos y Caninos</h3>
                            </div>
                            <div class="card-body">
                                <a href="<?php echo e(route('daily_reports.pase_lista_agrupamiento_equinos_caninos.index')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Armamento Equinos y Caninos</h3>
                            </div>
                            <div class="card-body">
                                <a href="<?php echo e(route('daily_reports.armamento_equinos_caninos.index')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-open"></i> Ver reportes
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/daily_reports/index.blade.php ENDPATH**/ ?>
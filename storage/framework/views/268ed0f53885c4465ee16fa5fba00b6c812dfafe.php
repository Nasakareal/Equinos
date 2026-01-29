

<?php $__env->startSection('title', 'Detalle de Asignación de Armamento'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Detalle de Asignación de Armamento</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">

    <!-- Información de la asignación -->
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos de la Asignación</h3>

                <div class="card-tools">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar armamento')): ?>
                        <a href="<?php echo e(route('armamento_asignaciones.edit', $weapon_assignment->id)); ?>"
                           class="btn btn-success btn-sm">
                            <i class="fa-regular fa-pen-to-square"></i> Editar
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Estatus</th>
                            <td>
                                <?php if($weapon_assignment->status === 'ASIGNADA'): ?>
                                    <span class="badge badge-success">ASIGNADA</span>
                                <?php elseif($weapon_assignment->status === 'DEVUELTA'): ?>
                                    <span class="badge badge-secondary">DEVUELTA</span>
                                <?php elseif($weapon_assignment->status === 'CANCELADA'): ?>
                                    <span class="badge badge-danger">CANCELADA</span>
                                <?php else: ?>
                                    <span class="badge badge-dark"><?php echo e($weapon_assignment->status); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de asignación</th>
                            <td><?php echo e(optional($weapon_assignment->fecha_asignacion)->format('d/m/Y') ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Fecha de devolución</th>
                            <td><?php echo e(optional($weapon_assignment->fecha_devolucion)->format('d/m/Y') ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Observaciones</th>
                            <td style="white-space: pre-wrap;">
                                <?php echo e($weapon_assignment->observaciones ?? '-'); ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Fecha de registro</th>
                            <td><?php echo e(optional($weapon_assignment->created_at)->format('d/m/Y H:i')); ?></td>
                        </tr>

                        <tr>
                            <th>Última actualización</th>
                            <td><?php echo e(optional($weapon_assignment->updated_at)->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="<?php echo e(route('armamento_asignaciones.index')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <!-- Información del arma y personal -->
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Arma y Personal</h3>
            </div>

            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th style="width:35%">Personal</th>
                            <td><?php echo e($weapon_assignment->personal->nombres ?? '—'); ?></td>
                        </tr>

                        <tr>
                            <th>Arma</th>
                            <td>
                                <?php echo e($weapon_assignment->weapon->tipo ?? '—'); ?>

                                — <?php echo e($weapon_assignment->weapon->matricula ?? '—'); ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Marca / Modelo</th>
                            <td><?php echo e($weapon_assignment->weapon->marca_modelo ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Estado actual del arma</th>
                            <td>
                                <?php if($weapon_assignment->weapon->estado === 'ACTIVA'): ?>
                                    <span class="badge badge-success">ACTIVA</span>
                                <?php elseif($weapon_assignment->weapon->estado === 'INACTIVA'): ?>
                                    <span class="badge badge-secondary">INACTIVA</span>
                                <?php elseif($weapon_assignment->weapon->estado === 'BAJA'): ?>
                                    <span class="badge badge-danger">BAJA</span>
                                <?php else: ?>
                                    <span class="badge badge-dark"><?php echo e($weapon_assignment->weapon->estado); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .table th {
        background-color: rgba(255,255,255,.08);
        color: #ffffff;
        white-space: nowrap;
    }

    .table td {
        color: #ffffff;
        vertical-align: middle;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/armamento_asignaciones/show.blade.php ENDPATH**/ ?>
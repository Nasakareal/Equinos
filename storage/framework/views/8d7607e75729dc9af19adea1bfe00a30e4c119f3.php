

<?php $__env->startSection('title', 'Historial Médico'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Historial Médico - <?php echo e($animal->nombre); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">Registros Médicos</h3>

                <div class="card-tools">
                    <a href="<?php echo e(route('animales.medico.create', $animal)); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Registro
                    </a>
                </div>
            </div>

            <div class="card-body">

                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if($records->isEmpty()): ?>
                    <div class="alert alert-info">
                        Este animal aún no tiene registros médicos.
                    </div>
                <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Veterinario</th>
                                <th>Costo</th>
                                <th>Próxima cita</th>
                                <th>Archivos</th>
                                <th width="180">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($record->fecha); ?></td>
                                    <td><?php echo e($record->tipo); ?></td>
                                    <td><?php echo e($record->veterinario ?? '-'); ?></td>
                                    <td><?php echo e($record->costo ? '$'.$record->costo : '-'); ?></td>
                                    <td><?php echo e($record->proxima_cita ?? '-'); ?></td>

                                    
                                    <td>
                                        <?php if($record->files->count()): ?>
                                            <?php $__currentLoopData = $record->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="mb-1">
                                                    <a href="<?php echo e(Storage::url($file->archivo)); ?>" target="_blank">
                                                        <i class="fas fa-file"></i> <?php echo e($file->tipo); ?>

                                                    </a>

                                                    <form action="<?php echo e(route('animales.medico.files.destroy', $file)); ?>"
                                                          method="POST"
                                                          style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Sin archivos</span>
                                        <?php endif; ?>

                                        
                                        <form action="<?php echo e(route('animales.medico.files.store', [$animal, $record])); ?>"
                                              method="POST"
                                              enctype="multipart/form-data"
                                              class="mt-2">
                                            <?php echo csrf_field(); ?>
                                            <input type="file" name="archivo" required class="form-control form-control-sm mb-1">
                                            <button class="btn btn-xs btn-info btn-block">
                                                Subir
                                            </button>
                                        </form>
                                    </td>

                                    
                                    <td>
                                        <a href="<?php echo e(route('animales.medico.edit', [$animal, $record])); ?>"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="<?php echo e(route('animales.medico.destroy', [$animal, $record])); ?>"
                                              method="POST"
                                              style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar registro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>

                                
                                <?php if($record->descripcion): ?>
                                <tr>
                                    <td colspan="7">
                                        <strong>Descripción:</strong><br>
                                        <?php echo e($record->descripcion); ?>

                                    </td>
                                </tr>
                                <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/animales/medico/index.blade.php ENDPATH**/ ?>
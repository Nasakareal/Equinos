

<?php $__env->startSection('title', 'Horario Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Horario de <?php echo e($personal->nombres); ?></h1>
        <div>
            <a href="<?php echo e(route('personal.show', $personal->id)); ?>" class="btn btn-info">
                <i class="fa-regular fa-eye"></i> Ver perfil
            </a>
            <a href="<?php echo e(route('personal.index')); ?>" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa-regular fa-clock"></i> Configurar horario (turno MIXTO / personalizado)
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Área</div>
                            <div class="font-weight-bold"><?php echo e($personal->area->nombre ?? '-'); ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Turno</div>
                            <div class="font-weight-bold"><?php echo e($personal->turno->nombre ?? '-'); ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Dependencia</div>
                            <div class="font-weight-bold"><?php echo e($personal->dependencia ?? '-'); ?></div>
                        </div>
                    </div>

                    <?php
                        $dias = [
                            0 => 'Lunes',
                            1 => 'Martes',
                            2 => 'Miércoles',
                            3 => 'Jueves',
                            4 => 'Viernes',
                            5 => 'Sábado',
                            6 => 'Domingo',
                        ];

                        $horariosPorDia = [];

                        if (isset($horario) && isset($horario->detalles) && is_iterable($horario->detalles)) {
                            foreach ($horario->detalles as $h) {
                                $k = (int)($h->dia_semana ?? -1);
                                if (!isset($horariosPorDia[$k])) $horariosPorDia[$k] = [];
                                $horariosPorDia[$k][] = $h;
                            }
                        }

                        foreach ($horariosPorDia as $k => $arr) {
                            usort($arr, function ($a, $b) {
                                return strcmp((string)($a->hora_entrada ?? ''), (string)($b->hora_entrada ?? ''));
                            });
                            $horariosPorDia[$k] = $arr;
                        }

                        $bloques = ['A', 'B', 'C', 'D', 'E', 'F', 'GENERAL'];
                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 140px; text-align:center;">Día</th>
                                    <th>Tramos</th>
                                    <th style="width: 460px; text-align:center;">Agregar tramo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diaKey => $diaLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $tramos = $horariosPorDia[$diaKey] ?? [];
                                    ?>
                                    <tr>
                                        <td class="text-center font-weight-bold align-middle"><?php echo e($diaLabel); ?></td>

                                        <td class="align-middle">
                                            <?php if(count($tramos) === 0): ?>
                                                <span class="badge badge-secondary">Sin tramos</span>
                                            <?php else: ?>
                                                <div class="d-flex flex-wrap" style="gap:10px;">
                                                    <?php $__currentLoopData = $tramos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $hi = substr((string)($t->hora_entrada ?? ''), 0, 5);
                                                            $hf = substr((string)($t->hora_salida ?? ''), 0, 5);
                                                            $tb = trim((string)($t->bloque ?? ''));
                                                        ?>
                                                        <div class="border rounded px-2 py-1">
                                                            <div class="font-weight-bold">
                                                                <?php echo e($hi); ?> - <?php echo e($hf); ?>

                                                                <?php if(!empty($t->cruza_dia)): ?>
                                                                    <span class="badge badge-warning">Cruza día</span>
                                                                <?php endif; ?>
                                                                <?php if($tb !== ''): ?>
                                                                    <span class="badge badge-info"><?php echo e($tb); ?></span>
                                                                <?php endif; ?>
                                                            </div>

                                                            <?php if(!empty($t->notas)): ?>
                                                                <div class="text-muted small"><?php echo e($t->notas); ?></div>
                                                            <?php endif; ?>

                                                            <div class="mt-2 d-flex" style="gap:6px;">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-success btn-edit-tramo"
                                                                        data-id="<?php echo e($t->id); ?>"
                                                                        data-dia_semana="<?php echo e((int)$diaKey); ?>"
                                                                        data-inicio="<?php echo e($hi); ?>"
                                                                        data-fin="<?php echo e($hf); ?>"
                                                                        data-cruza="<?php echo e((int)($t->cruza_dia ?? 0)); ?>"
                                                                        data-notas="<?php echo e($t->notas ?? ''); ?>"
                                                                        data-bloque="<?php echo e($tb); ?>">
                                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                                </button>

                                                                <form action="<?php echo e(route('personal.horario_detalles.destroy', [$personal->id, $horario->id, $t->id])); ?>"
                                                                      method="POST"
                                                                      class="form-delete-tramo d-inline">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-tramo">
                                                                        <i class="fa-regular fa-trash-can"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td class="align-middle">
                                            <form action="<?php echo e(route('personal.horario_detalles.store', [$personal->id, $horario->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="dia_semana" value="<?php echo e((int)$diaKey); ?>">

                                                <div class="form-row">
                                                    <div class="col">
                                                        <input type="time"
                                                               name="hora_inicio"
                                                               class="form-control form-control-sm <?php $__errorArgs = ['hora_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                               value="<?php echo e(old('hora_inicio')); ?>"
                                                               required>
                                                    </div>

                                                    <div class="col">
                                                        <input type="time"
                                                               name="hora_fin"
                                                               class="form-control form-control-sm <?php $__errorArgs = ['hora_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                               value="<?php echo e(old('hora_fin')); ?>"
                                                               required>
                                                    </div>

                                                    <div class="col-auto d-flex align-items-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                   class="custom-control-input"
                                                                   id="cruza_<?php echo e($diaKey); ?>"
                                                                   name="cruza_dia"
                                                                   value="1"
                                                                   <?php echo e(old('cruza_dia') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="cruza_<?php echo e($diaKey); ?>">Cruza día</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row mt-2">
                                                    <div class="col">
                                                        <select name="bloque" class="form-control form-control-sm <?php $__errorArgs = ['bloque'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                                            <?php $__currentLoopData = $bloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($b); ?>" <?php echo e(old('bloque', 'A') === $b ? 'selected' : ''); ?>><?php echo e($b); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="col">
                                                        <input type="text"
                                                               name="notas"
                                                               class="form-control form-control-sm <?php $__errorArgs = ['notas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                               value="<?php echo e(old('notas')); ?>"
                                                               placeholder="Notas (opcional)">
                                                    </div>

                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fa-solid fa-plus"></i> Agregar
                                                        </button>
                                                    </div>
                                                </div>

                                                <?php $__errorArgs = ['dia_semana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger mt-3">
                            <div class="font-weight-bold mb-2">Errores</div>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($e); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditTramo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditTramo" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-regular fa-clock"></i> Editar tramo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="dia_semana" id="edit_dia_semana">

                        <div class="form-group">
                            <label for="edit_hora_inicio">Hora inicio</label>
                            <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_hora_fin">Hora fin</label>
                            <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="edit_cruza_dia" name="cruza_dia" value="1">
                                <label class="custom-control-label" for="edit_cruza_dia">Cruza día</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_bloque">Bloque</label>
                            <select name="bloque" id="edit_bloque" class="form-control" required>
                                <?php $__currentLoopData = $bloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b); ?>"><?php echo e($b); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_notas">Notas</label>
                            <input type="text" name="notas" id="edit_notas" class="form-control" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa-solid fa-ban"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .table th, .table td { vertical-align: middle; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        (function () {
            $(document).on('click', '.btn-delete-tramo', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: '¿Eliminar tramo?',
                    text: 'Esta acción no se puede revertir.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            $(document).on('click', '.btn-edit-tramo', function () {
                const tramoId = $(this).data('id');
                const diaSemana = parseInt($(this).data('dia_semana'), 10);
                const inicio = $(this).data('inicio');
                const fin = $(this).data('fin');
                const cruza = parseInt($(this).data('cruza') || 0, 10);
                const notas = $(this).data('notas') || '';
                const bloque = ($(this).data('bloque') || 'A').toString();

                $('#edit_dia_semana').val(diaSemana);
                $('#edit_hora_inicio').val(inicio);
                $('#edit_hora_fin').val(fin);
                $('#edit_cruza_dia').prop('checked', cruza === 1);
                $('#edit_notas').val(notas);
                $('#edit_bloque').val(bloque);

                const url = "<?php echo e(route('personal.horario_detalles.update', [$personal->id, $horario->id, 'DETALLE_ID'])); ?>".replace('DETALLE_ID', tramoId);
                $('#formEditTramo').attr('action', url);

                $('#modalEditTramo').modal('show');
            });

            <?php if(session('success')): ?>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '<?php echo e(session('success')); ?>',
                    showConfirmButton: false,
                    timer: 9000
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '<?php echo e(session('error')); ?>',
                    showConfirmButton: true
                });
            <?php endif; ?>
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/horario.blade.php ENDPATH**/ ?>
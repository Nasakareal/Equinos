

<?php $__env->startSection('title', 'Detalle de Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Detalle de Personal</h1>

        <div class="btn-group">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear armamento')): ?>
                <a href="<?php echo e(route('armamento_asignaciones.create', ['personal_id' => $personal->id])); ?>"
                   class="btn btn-primary">
                    <i class="fa-solid fa-gun"></i> Asignar arma
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear incidencias')): ?>
                <a href="<?php echo e(route('incidencias.create', ['personal_id' => $personal->id])); ?>"
                   class="btn btn-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i> Registrar incidencia
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                <a href="<?php echo e(route('personal.documentos.index', $personal->id)); ?>"
                   class="btn btn-info">
                    <i class="fa-solid fa-folder-open"></i> Documentos
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Información General</h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            <strong>Nombre completo</strong>
                            <p class="text-muted"><?php echo e($personal->nombres); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Grado</strong>
                            <p class="text-muted"><?php echo e($personal->grado ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Cargo</strong>
                            <p class="text-muted"><?php echo e($personal->cargo ?? '—'); ?></p>
                        </div>

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Usuario del sistema</strong>
                            <p class="text-muted">
                                <?php if($personal->user): ?>
                                    <?php echo e($personal->user->name); ?> <br>
                                    <small><?php echo e($personal->user->email); ?></small>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <strong>No. empleado</strong>
                            <p class="text-muted"><?php echo e($personal->no_empleado ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Área</strong>
                            <p class="text-muted"><?php echo e($personal->area->nombre ?? 'Sin área'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>CUIP</strong>
                            <p class="text-muted"><?php echo e($personal->cuip ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>CRP</strong>
                            <p class="text-muted"><?php echo e($personal->crp ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Celular</strong>
                            <p class="text-muted"><?php echo e($personal->celular ?? '—'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Actividad</strong>
                            <p class="text-muted"><?php echo e($personal->actividad ?? '—'); ?></p>
                        </div>

                        <div class="col-md-4">
                            <strong>Área de patrullaje</strong>
                            <p class="text-muted"><?php echo e($personal->area_patrullaje ?? '—'); ?></p>
                        </div>

                        <div class="col-md-2">
                            <strong>Responsable</strong>
                            <p class="text-muted">
                                <?php if($personal->es_responsable): ?>
                                    <span class="badge badge-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">No</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-2">
                            <strong>Estatus</strong>
                            <p class="text-muted">
                                <?php if($personal->activo): ?>
                                    <span class="badge badge-primary">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Observaciones</strong>
                            <p class="text-muted"><?php echo e($personal->observaciones ?: 'Sin observaciones'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo e(route('personal.index')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    <div class="btn-group">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                            <a href="<?php echo e(route('personal.edit', $personal->id)); ?>" class="btn btn-success">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-info">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">
                        <i class="fa-solid fa-folder-open"></i> Documentos
                    </h3>

                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                            <a href="<?php echo e(route('personal.documentos.index', $personal->id)); ?>" class="btn btn-info btn-sm">
                                <i class="fa-solid fa-magnifying-glass"></i> Ver todos
                            </a>

                            <a href="<?php echo e(route('personal.documentos.create', $personal->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-upload"></i> Subir documento
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(isset($personal->documentos) && $personal->documentos->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Archivo</th>
                                        <th>Fecha</th>
                                        <th style="width: 180px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $personal->documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($documento->titulo); ?></td>
                                            <td><?php echo e($documento->tipo_documento ?? '—'); ?></td>
                                            <td><?php echo e($documento->nombre_original ?? '—'); ?></td>
                                            <td>
                                                <?php if($documento->fecha_documento): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y')); ?>

                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
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

                                                        <form action="<?php echo e(route('personal.documentos.destroy', [$personal->id, $documento->id])); ?>"
                                                              method="POST"
                                                              style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit"
                                                                    class="btn btn-danger btn-sm"
                                                                    title="Eliminar"
                                                                    onclick="return confirm('¿Deseas eliminar este documento?')">
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
                    <?php else: ?>
                        <span class="badge badge-secondary">Sin documentos registrados</span>
                    <?php endif; ?>
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
            ?>

            <div class="card card-outline card-primary">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fa-regular fa-clock"></i> Horario</h3>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar personal')): ?>
                        <a href="<?php echo e(route('personal.horario.edit', $personal->id)); ?>" class="btn btn-primary btn-sm">
                            <i class="fa-regular fa-clock"></i> Configurar
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 140px; text-align:center;">Día</th>
                                    <th>Tramos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diaKey => $diaLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $tramos = $horariosPorDia[$diaKey] ?? []; ?>
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
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa-solid fa-gun"></i> Armamento asignado</h3>
                </div>

                <div class="card-body">
                    <?php if(isset($armasActivas) && $armasActivas->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Estado</th>
                                        <th>Fecha asignación</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $armasActivas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($a->weapon->tipo ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->matricula ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->estado ?? 'N/D'); ?></td>
                                            <td>
                                                <?php
                                                    $fa = $a->fecha_asignacion;
                                                    $faTxt = 'N/D';
                                                    if ($fa instanceof \Carbon\Carbon) $faTxt = $fa->format('d/m/Y H:i');
                                                    elseif (!empty($fa)) $faTxt = \Carbon\Carbon::parse($fa)->format('d/m/Y H:i');
                                                ?>
                                                <?php echo e($faTxt); ?>

                                            </td>
                                            <td><?php echo e($a->observaciones ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-secondary">Sin arma asignada</span>
                    <?php endif; ?>

                    <?php if(isset($historialArmamento) && $historialArmamento->count()): ?>
                        <hr>
                        <h5 class="mb-3"><i class="fa-solid fa-clock-rotate-left"></i> Historial</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Matrícula</th>
                                        <th>Status</th>
                                        <th>Asignación</th>
                                        <th>Devolución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $historialArmamento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($a->weapon->tipo ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->weapon->matricula ?? 'N/D'); ?></td>
                                            <td><?php echo e($a->status ?? 'N/D'); ?></td>
                                            <td>
                                                <?php
                                                    $fa = $a->fecha_asignacion;
                                                    $faTxt = 'N/D';
                                                    if ($fa instanceof \Carbon\Carbon) $faTxt = $fa->format('d/m/Y H:i');
                                                    elseif (!empty($fa)) $faTxt = \Carbon\Carbon::parse($fa)->format('d/m/Y H:i');
                                                ?>
                                                <?php echo e($faTxt); ?>

                                            </td>
                                            <td>
                                                <?php
                                                    $fd = $a->fecha_devolucion;
                                                    $fdTxt = '---';
                                                    if ($fd instanceof \Carbon\Carbon) $fdTxt = $fd->format('d/m/Y H:i');
                                                    elseif (!empty($fd)) $fdTxt = \Carbon\Carbon::parse($fd)->format('d/m/Y H:i');
                                                ?>
                                                <?php echo e($fdTxt); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card card-outline card-purple">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fa-solid fa-file-pdf"></i> Puestas a disposición</h3>

                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear puestas a disposicion')): ?>
                            <a href="<?php echo e(route('puestas_disposicion.create', ['personal_id' => $personal->id])); ?>" class="btn btn-purple btn-sm">
                                <i class="fa-solid fa-plus"></i> Registrar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(isset($puestasDisposicion) && $puestasDisposicion->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Año</th>
                                        <th>Hecho</th>
                                        <th>Archivo</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $puestasDisposicion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($pd->folio ?? ('—')); ?></td>
                                            <td class="text-center"><?php echo e($pd->anio ?? '—'); ?></td>
                                            <td class="text-center"><?php echo e($pd->hecho_id ?? '—'); ?></td>
                                            <td class="text-center">
                                                <?php if(!empty($pd->archivo_pdf)): ?>
                                                    <a href="<?php echo e(asset('storage/'.$pd->archivo_pdf)); ?>" target="_blank" class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                                    </a>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($pd->observaciones ?? ''); ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver puestas a disposicion')): ?>
                                                        <a href="<?php echo e(route('puestas_disposicion.show', $pd->id)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar puestas a disposicion')): ?>
                                                        <a href="<?php echo e(route('puestas_disposicion.edit', $pd->id)); ?>" class="btn btn-success btn-sm" title="Editar">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar puestas a disposicion')): ?>
                                                        <form action="<?php echo e(route('puestas_disposicion.destroy', $pd->id)); ?>" method="POST" style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn-pd" title="Eliminar">
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
                    <?php else: ?>
                        <span class="badge badge-secondary">Sin puestas a disposición registradas</span>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        strong { display:block; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/personal/show.blade.php ENDPATH**/ ?>
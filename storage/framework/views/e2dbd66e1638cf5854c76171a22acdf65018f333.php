

<?php $__env->startSection('title', 'Editar Reporte'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Editar Reporte Operativo</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('mis_servicios.reportes.update', [$servicio->id, $reporte->id])); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Datos base del servicio</h3>
                        <div class="card-tools">
                            <a href="<?php echo e(route('mis_servicios.reportes.show', [$servicio->id, $reporte->id])); ?>" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label><strong>Fecha del servicio</strong></label>
                                <input type="text" class="form-control" value="<?php echo e(optional($servicio->fecha)->format('d/m/Y')); ?>" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Hora</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($servicio->hora ? \Carbon\Carbon::parse($servicio->hora)->format('H:i') : '-'); ?>" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Tipo de servicio</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($servicio->tipo_servicio ?? '-'); ?>" readonly>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Municipio</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($servicio->municipio ?? '-'); ?>" readonly>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label><strong>Asunto base</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($servicio->asunto ?? '-'); ?>" readonly>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label><strong>Lugar base</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($servicio->lugar ?? '-'); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <strong>Revisa esto:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Edición del reporte</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_reporte">Tipo de reporte</label>
                                    <select name="tipo_reporte" id="tipo_reporte" class="form-control <?php $__errorArgs = ['tipo_reporte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Seleccione...</option>
                                        <option value="INICIO" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'INICIO' ? 'selected' : ''); ?>>INICIO</option>
                                        <option value="CONTINUIDAD" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'CONTINUIDAD' ? 'selected' : ''); ?>>CONTINUIDAD</option>
                                        <option value="FINALIZACION" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'FINALIZACION' ? 'selected' : ''); ?>>FINALIZACIÓN</option>
                                        <option value="INCIDENTE" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'INCIDENTE' ? 'selected' : ''); ?>>INCIDENTE</option>
                                        <option value="RESULTADO" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'RESULTADO' ? 'selected' : ''); ?>>RESULTADO</option>
                                        <option value="PUESTA_DISPOSICION" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'PUESTA_DISPOSICION' ? 'selected' : ''); ?>>PUESTA A DISPOSICIÓN</option>
                                        <option value="APOYO_BUSQUEDA" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'APOYO_BUSQUEDA' ? 'selected' : ''); ?>>APOYO BÚSQUEDA</option>
                                        <option value="EVENTO" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'EVENTO' ? 'selected' : ''); ?>>EVENTO</option>
                                        <option value="OTRO" <?php echo e(old('tipo_reporte', $reporte->tipo_reporte) == 'OTRO' ? 'selected' : ''); ?>>OTRO</option>
                                    </select>
                                    <?php $__errorArgs = ['tipo_reporte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fecha', optional($reporte->fecha)->format('Y-m-d'))); ?>" required>
                                    <?php $__errorArgs = ['fecha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hora">Hora</label>
                                    <input type="time" name="hora" id="hora" class="form-control <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('hora', $reporte->hora ? \Carbon\Carbon::parse($reporte->hora)->format('H:i') : '')); ?>">
                                    <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="municipio">Municipio</label>
                                    <input type="text" name="municipio" id="municipio" class="form-control <?php $__errorArgs = ['municipio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('municipio', $reporte->municipio)); ?>">
                                    <?php $__errorArgs = ['municipio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asunto">Asunto</label>
                                    <input type="text" name="asunto" id="asunto" class="form-control <?php $__errorArgs = ['asunto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('asunto', $reporte->asunto)); ?>">
                                    <?php $__errorArgs = ['asunto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lugar">Lugar</label>
                                    <input type="text" name="lugar" id="lugar" class="form-control <?php $__errorArgs = ['lugar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('lugar', $reporte->lugar)); ?>">
                                    <?php $__errorArgs = ['lugar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lat">Latitud</label>
                                    <input type="number" step="0.0000001" name="lat" id="lat" class="form-control <?php $__errorArgs = ['lat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('lat', $reporte->lat)); ?>">
                                    <?php $__errorArgs = ['lat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lng">Longitud</label>
                                    <input type="number" step="0.0000001" name="lng" id="lng" class="form-control <?php $__errorArgs = ['lng'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('lng', $reporte->lng)); ?>">
                                    <?php $__errorArgs = ['lng'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="narrativa">Narrativa</label>
                                    <textarea name="narrativa" id="narrativa" rows="6" class="form-control <?php $__errorArgs = ['narrativa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('narrativa', $reporte->narrativa)); ?></textarea>
                                    <?php $__errorArgs = ['narrativa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="estado_fuerza_texto">Estado de fuerza</label>
                                    <textarea name="estado_fuerza_texto" id="estado_fuerza_texto" rows="4" class="form-control <?php $__errorArgs = ['estado_fuerza_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('estado_fuerza_texto', $reporte->estado_fuerza_texto)); ?></textarea>
                                    <?php $__errorArgs = ['estado_fuerza_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="acciones_a_realizar">Acciones a realizar</label>
                                    <textarea name="acciones_a_realizar" id="acciones_a_realizar" rows="4" class="form-control <?php $__errorArgs = ['acciones_a_realizar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('acciones_a_realizar', $reporte->acciones_a_realizar)); ?></textarea>
                                    <?php $__errorArgs = ['acciones_a_realizar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="acciones_realizadas">Acciones realizadas</label>
                                    <textarea name="acciones_realizadas" id="acciones_realizadas" rows="4" class="form-control <?php $__errorArgs = ['acciones_realizadas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('acciones_realizadas', $reporte->acciones_realizadas)); ?></textarea>
                                    <?php $__errorArgs = ['acciones_realizadas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="resultados">Resultados</label>
                                    <textarea name="resultados" id="resultados" rows="4" class="form-control <?php $__errorArgs = ['resultados'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('resultados', $reporte->resultados)); ?></textarea>
                                    <?php $__errorArgs = ['resultados'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12" id="bloque_datos_persona">
                                <div class="form-group">
                                    <label for="datos_persona_asegurada">Datos de la persona asegurada</label>
                                    <textarea name="datos_persona_asegurada" id="datos_persona_asegurada" rows="5" class="form-control <?php $__errorArgs = ['datos_persona_asegurada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('datos_persona_asegurada', $reporte->datos_persona_asegurada)); ?></textarea>
                                    <?php $__errorArgs = ['datos_persona_asegurada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="conclusion">Conclusión / cierre</label>
                                    <textarea name="conclusion" id="conclusion" rows="4" class="form-control <?php $__errorArgs = ['conclusion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('conclusion', $reporte->conclusion)); ?></textarea>
                                    <?php $__errorArgs = ['conclusion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <?php if($reporte->fotos && $reporte->fotos->count()): ?>
                                <div class="col-md-12">
                                    <hr>
                                    <h5>Fotos actuales</h5>
                                </div>

                                <?php $__currentLoopData = $reporte->fotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-outline card-primary h-100">
                                            <div class="card-body text-center">
                                                <?php
                                                    $fotoUrl = !empty($foto->ruta) ? Storage::url($foto->ruta) : null;
                                                ?>

                                                <?php if($fotoUrl): ?>
                                                    <a href="<?php echo e($fotoUrl); ?>" target="_blank" rel="noopener">
                                                        <img
                                                            src="<?php echo e($fotoUrl); ?>"
                                                            alt="Foto del reporte"
                                                            class="img-fluid rounded shadow"
                                                            style="width: 100%; height: 220px; object-fit: cover;"
                                                        >
                                                    </a>
                                                <?php else: ?>
                                                    <div class="text-muted py-5">
                                                        <i class="fa-regular fa-image fa-2x mb-2"></i>
                                                        <div>Imagen no disponible</div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="card-footer">
                                                <div><strong>Descripción:</strong> <?php echo e($foto->descripcion ?? 'Sin descripción'); ?></div>
                                                <div class="text-muted small mt-1">
                                                    <?php echo e($foto->created_at ? $foto->created_at->format('d/m/Y H:i') : ''); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <div class="col-md-12">
                                <hr>
                                <h5>Agregar nueva evidencia fotográfica</h5>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fotos">Fotos</label>
                                    <input type="file" name="fotos[]" id="fotos" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted">Puedes seleccionar varias imágenes nuevas.</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="contenedor_descripciones_fotos" class="row"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="<?php echo e(route('mis_servicios.reportes.show', [$servicio->id, $reporte->id])); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Actualizar reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    function toggleDatosPersona() {
        const tipo = document.getElementById('tipo_reporte').value;
        const bloque = document.getElementById('bloque_datos_persona');

        if (tipo === 'PUESTA_DISPOSICION' || tipo === 'INCIDENTE') {
            bloque.style.display = '';
        } else {
            bloque.style.display = 'none';
        }
    }

    function renderDescripcionesFotos() {
        const input = document.getElementById('fotos');
        const contenedor = document.getElementById('contenedor_descripciones_fotos');
        contenedor.innerHTML = '';

        Array.from(input.files).forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';

            col.innerHTML = `
                <div class="form-group">
                    <label>Descripción para: ${file.name}</label>
                    <input type="text" name="descripcion[${index}]" class="form-control" maxlength="255" placeholder="Descripción opcional de la foto">
                </div>
            `;

            contenedor.appendChild(col);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleDatosPersona();

        document.getElementById('tipo_reporte').addEventListener('change', toggleDatosPersona);
        document.getElementById('fotos').addEventListener('change', renderDescripcionesFotos);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/mis_servicios/reportes/edit.blade.php ENDPATH**/ ?>
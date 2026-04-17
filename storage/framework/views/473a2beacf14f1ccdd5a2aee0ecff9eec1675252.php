

<?php $__env->startSection('title', 'Registrar Equinoterapia'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Registrar Equinoterapia</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<form action="<?php echo e(route('equinoterapias.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h3 class="card-title">Datos generales del reporte</h3>

                    <div class="card-tools">
                        <a href="<?php echo e(route('equinoterapias.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
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
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fecha', now()->format('Y-m-d'))); ?>" required>
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
                                <label for="personal">Personal</label>
                                <input type="number" name="personal" id="personal" class="form-control <?php $__errorArgs = ['personal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" value="<?php echo e(old('personal', 0)); ?>">
                                <?php $__errorArgs = ['personal'];
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
                                <label for="equinos">Equinos</label>
                                <input type="number" name="equinos" id="equinos" class="form-control <?php $__errorArgs = ['equinos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" value="<?php echo e(old('equinos', 0)); ?>">
                                <?php $__errorArgs = ['equinos'];
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
                                <label for="valoraciones">Valoraciones</label>
                                <input type="number" name="valoraciones" id="valoraciones" class="form-control <?php $__errorArgs = ['valoraciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" value="<?php echo e(old('valoraciones', 0)); ?>">
                                <?php $__errorArgs = ['valoraciones'];
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
                                <label for="actividades_area">Actividades del área</label>
                                <textarea name="actividades_area" id="actividades_area" rows="3" class="form-control <?php $__errorArgs = ['actividades_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('actividades_area', 'ASEO Y MANTENIMIENTO DE TODA EL ÁREA')); ?></textarea>
                                <?php $__errorArgs = ['actividades_area'];
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
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="3" class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('observaciones')); ?></textarea>
                                <?php $__errorArgs = ['observaciones'];
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
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3 id="total_terapias">0</h3>
                                    <p>Terapias</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-horse"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3 id="total_inasistencias">0</h3>
                                    <p>Inasistencias</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-pink">
                                <div class="inner">
                                    <h3 id="total_ninas">0</h3>
                                    <p>Niñas</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-child-dress"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 id="total_ninos">0</h3>
                                    <p>Niños</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-child"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3 id="total_valoraciones">0</h3>
                                    <p>Valoraciones</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-notes-medical"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 id="total_registros">0</h3>
                                    <p>Registros</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h3 class="card-title">Registros de asistencia</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-pink btn-sm" id="btn-agregar-fila">
                            <i class="fa-solid fa-plus"></i> Agregar registro
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            Verifica la información capturada.
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="tabla-registros">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre completo</th>
                                    <th style="width: 120px;">Sexo</th>
                                    <th>Diagnóstico</th>
                                    <th style="width: 140px;">Asistencia</th>
                                    <th style="width: 130px;">Valoración</th>
                                    <th>Motivo de inasistencia</th>
                                    <th style="width: 70px;">Quitar</th>
                                </tr>
                            </thead>
                            <tbody id="contenedor-registros">
                                <?php
                                    $oldNombres = old('nombre_completo', ['']);
                                    $oldSexos = old('sexo', ['NIÑO']);
                                    $oldDiagnosticos = old('diagnostico', ['']);
                                    $oldAsistencias = old('estatus_asistencia', ['ASISTIO']);
                                    $oldMotivos = old('motivo_inasistencia', ['']);
                                    $oldValoraciones = old('es_valoracion', ['0']);
                                ?>

                                <?php $__currentLoopData = $oldNombres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $oldNombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="fila-registro">
                                        <td class="fila-numero"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <input type="text" name="nombre_completo[]" class="form-control" value="<?php echo e($oldNombre); ?>" required>
                                        </td>
                                        <td>
                                            <select name="sexo[]" class="form-control select-sexo">
                                                <option value="NIÑO" <?php echo e(($oldSexos[$i] ?? 'NIÑO') == 'NIÑO' ? 'selected' : ''); ?>>NIÑO</option>
                                                <option value="NIÑA" <?php echo e(($oldSexos[$i] ?? '') == 'NIÑA' ? 'selected' : ''); ?>>NIÑA</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="diagnostico[]" class="form-control" value="<?php echo e($oldDiagnosticos[$i] ?? ''); ?>">
                                        </td>
                                        <td>
                                            <select name="estatus_asistencia[]" class="form-control select-asistencia">
                                                <option value="ASISTIO" <?php echo e(($oldAsistencias[$i] ?? 'ASISTIO') == 'ASISTIO' ? 'selected' : ''); ?>>ASISTIÓ</option>
                                                <option value="INASISTIO" <?php echo e(($oldAsistencias[$i] ?? '') == 'INASISTIO' ? 'selected' : ''); ?>>INASISTIÓ</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="es_valoracion[]" class="form-control select-valoracion">
                                                <option value="0" <?php echo e(($oldValoraciones[$i] ?? '0') == '0' ? 'selected' : ''); ?>>NO</option>
                                                <option value="1" <?php echo e(($oldValoraciones[$i] ?? '') == '1' ? 'selected' : ''); ?>>SÍ</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="motivo_inasistencia[]" class="form-control input-motivo" value="<?php echo e($oldMotivos[$i] ?? ''); ?>" <?php echo e(($oldAsistencias[$i] ?? 'ASISTIO') == 'ASISTIO' ? 'disabled' : ''); ?>>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm btn-quitar-fila">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .table th, .table td{
        text-align:center;
        vertical-align:middle;
    }

    .bg-pink{
        background-color: #e83e8c !important;
        color: #fff !important;
    }

    .btn-pink{
        background-color: #e83e8c !important;
        border-color: #e83e8c !important;
        color: #fff !important;
    }

    .btn-pink:hover{
        background-color: #d63384 !important;
        border-color: #d63384 !important;
        color: #fff !important;
    }

    .card-outline.card-pink{
        border-top: 3px solid #e83e8c;
    }

    .small-box .icon i{
        font-size: 50px;
        top: 12px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
function actualizarNumerosFilas() {
    $('#contenedor-registros .fila-registro').each(function(index) {
        $(this).find('.fila-numero').text(index + 1);
    });
}

function actualizarEstadoMotivo() {
    $('#contenedor-registros .fila-registro').each(function() {
        let asistencia = $(this).find('.select-asistencia').val();
        let motivo = $(this).find('.input-motivo');

        if (asistencia === 'INASISTIO') {
            motivo.prop('disabled', false);
        } else {
            motivo.prop('disabled', true);
            motivo.val('');
        }
    });
}

function actualizarResumen() {
    let terapias = 0;
    let inasistencias = 0;
    let ninas = 0;
    let ninos = 0;
    let valoraciones = 0;
    let registros = 0;

    $('#contenedor-registros .fila-registro').each(function() {
        let nombre = $(this).find('input[name="nombre_completo[]"]').val().trim();
        let sexo = $(this).find('.select-sexo').val();
        let asistencia = $(this).find('.select-asistencia').val();
        let esValoracion = $(this).find('.select-valoracion').val();

        if (nombre !== '') {
            registros++;
        }

        if (nombre !== '' && asistencia === 'INASISTIO') {
            inasistencias++;
        }

        if (nombre !== '' && asistencia === 'ASISTIO' && esValoracion === '0') {
            terapias++;
        }

        if (nombre !== '' && asistencia === 'ASISTIO' && sexo === 'NIÑA') {
            ninas++;
        }

        if (nombre !== '' && asistencia === 'ASISTIO' && sexo === 'NIÑO') {
            ninos++;
        }

        if (nombre !== '' && esValoracion === '1') {
            valoraciones++;
        }
    });

    $('#total_terapias').text(terapias);
    $('#total_inasistencias').text(inasistencias);
    $('#total_ninas').text(ninas);
    $('#total_ninos').text(ninos);
    $('#total_valoraciones').text(valoraciones);
    $('#total_registros').text(registros);
    $('#valoraciones').val(valoraciones);
}

function crearFila() {
    return `
        <tr class="fila-registro">
            <td class="fila-numero"></td>
            <td>
                <input type="text" name="nombre_completo[]" class="form-control" required>
            </td>
            <td>
                <select name="sexo[]" class="form-control select-sexo">
                    <option value="NIÑO">NIÑO</option>
                    <option value="NIÑA">NIÑA</option>
                </select>
            </td>
            <td>
                <input type="text" name="diagnostico[]" class="form-control">
            </td>
            <td>
                <select name="estatus_asistencia[]" class="form-control select-asistencia">
                    <option value="ASISTIO">ASISTIÓ</option>
                    <option value="INASISTIO">INASISTIÓ</option>
                </select>
            </td>
            <td>
                <select name="es_valoracion[]" class="form-control select-valoracion">
                    <option value="0">NO</option>
                    <option value="1">SÍ</option>
                </select>
            </td>
            <td>
                <input type="text" name="motivo_inasistencia[]" class="form-control input-motivo" disabled>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-quitar-fila">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
}

$(function () {
    actualizarNumerosFilas();
    actualizarEstadoMotivo();
    actualizarResumen();

    $('#btn-agregar-fila').on('click', function() {
        $('#contenedor-registros').append(crearFila());
        actualizarNumerosFilas();
        actualizarEstadoMotivo();
        actualizarResumen();
    });

    $(document).on('click', '.btn-quitar-fila', function() {
        if ($('#contenedor-registros .fila-registro').length > 1) {
            $(this).closest('tr').remove();
            actualizarNumerosFilas();
            actualizarEstadoMotivo();
            actualizarResumen();
        } else {
            $(this).closest('tr').find('input[type="text"]').val('');
            $(this).closest('tr').find('.select-sexo').val('NIÑO');
            $(this).closest('tr').find('.select-asistencia').val('ASISTIO');
            $(this).closest('tr').find('.select-valoracion').val('0');
            $(this).closest('tr').find('.input-motivo').val('').prop('disabled', true);
            actualizarEstadoMotivo();
            actualizarResumen();
        }
    });

    $(document).on('change keyup', '.select-sexo, .select-asistencia, .select-valoracion, input[name="nombre_completo[]"], input[name="diagnostico[]"], .input-motivo', function() {
        actualizarEstadoMotivo();
        actualizarResumen();
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/equinoterapias/create.blade.php ENDPATH**/ ?>
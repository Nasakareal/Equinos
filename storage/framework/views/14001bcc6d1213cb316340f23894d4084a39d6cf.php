<?php $__env->startSection('title', 'Estado de Fuerza'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="ef-hero">
    <div class="ef-hero__inner">
        <div class="ef-hero__badge">
            <span class="ef-dot"></span>
            <span>Control operativo · Turnos · Armamento · Reportes</span>
        </div>

        <div class="ef-hero__title">
            Estado de Fuerza y Armamento
        </div>

        <div class="ef-hero__subtitle">
            Panel operativo para captura, incidencias y generación diaria de reportes
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">

    
    <div class="col-12">
        <div class="row">

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?php echo e($total_personal ?? 0); ?></h3>
                        <p>Total del personal</p>
                        <small><?php echo e(isset($now) ? $now->format('d/m/Y H:i') : ''); ?></small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e($total_laborando ?? 0); ?></h3>
                        <p>Laborando ahora</p>
                        <small>Según patrón de servicio</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <div class="card card-outline card-primary h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-chart-pie"></i> Estado de fuerza por dependencia (laborando)
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light">
                                <?php echo e(isset($now) ? $now->format('d/m/Y H:i') : ''); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Dependencia</th>
                                        <th class="text-center" style="width:140px;">Laborando</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $hayLaborando = isset($laborando_por_dependencia) && $laborando_por_dependencia->count() > 0;
                                    ?>

                                    <?php if($hayLaborando): ?>
                                        <?php $__currentLoopData = $laborando_por_dependencia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($row->dependencia ?? 'Sin dependencia'); ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-success"><?php echo e($row->total); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2"><center>Sin datos de laborando por dependencia.</center></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-2">

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Dependencia</th>
                                        <th class="text-center" style="width:140px;">Plantilla</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $hayTotales = isset($total_por_dependencia) && $total_por_dependencia->count() > 0;
                                    ?>

                                    <?php if($hayTotales): ?>
                                        <?php $__currentLoopData = $total_por_dependencia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($row->dependencia ?? 'Sin dependencia'); ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-primary"><?php echo e($row->total); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2"><center>Sin datos de plantilla por dependencia.</center></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <div class="col-12">
        <div class="ef-section">
            <div class="ef-section__title">
                <i class="fa-solid fa-bolt"></i> Operación diaria
            </div>
            <div class="ef-section__desc">
                Lo que se usa todos los días: personal, incidencias, servicio y reportes.
            </div>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver personal')): ?>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-primary">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Personal</div>
                <div class="ef-card__desc">Alta, edición y consulta del estado de fuerza.</div>
                <a href="<?php echo e(url('personal')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver incidencias')): ?>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Incidencias</div>
                <div class="ef-card__desc">Vacaciones, licencia, franco, comisión, etc.</div>
                <a href="<?php echo e(url('incidencias')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver reportes')): ?>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-success">
                <i class="fa-solid fa-file-excel"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Reportes diarios</div>
                <div class="ef-card__desc">
                    Generación y descarga de formatos diarios (6 excels).
                </div>

                <div class="d-flex gap-2" style="gap:8px;">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear reportes')): ?>
                    <form action="<?php echo e(route('daily_reports.generar')); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn ef-btn">
                            <i class="fa-solid fa-file-circle-plus"></i> Generar hoy
                        </button>
                    </form>
                    <?php endif; ?>

                    
                    <a href="<?php echo e(route('daily_reports.index')); ?>" class="btn ef-btn ef-btn--ghost">
                        <i class="fa-solid fa-list"></i> Historial
                    </a>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="col-12 mt-2">
        <div class="ef-section">
            <div class="ef-section__title">
                <i class="fa-solid fa-shield-halved"></i> Armamento
            </div>
            <div class="ef-section__desc">
                Inventario, asignación y control de armamento por personal.
            </div>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver armamento')): ?>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-navy">
                <i class="fa-solid fa-gun"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Inventario de armas</div>
                <div class="ef-card__desc">Alta y control de armas cortas y largas.</div>
                <a href="<?php echo e(url('armamento')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-maroon">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Asignación de armamento</div>
                <div class="ef-card__desc">Asignar/devolver armamento y ver historial.</div>
                <a href="<?php echo e(url('armamento-asignaciones')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card ef-card--soft">
            <div class="ef-card__icon bg-info">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Consulta rápida</div>
                <div class="ef-card__desc">Buscar por matrícula o por elemento (próximo).</div>
                <a href="#" class="btn ef-btn ef-btn--ghost" onclick="return false;">
                    <i class="fa-solid fa-clock"></i> Próximamente
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="col-12 mt-2">
        <div class="ef-section">
            <div class="ef-section__title">
                <i class="fa-solid fa-calendar-days"></i> Turnos y servicio
            </div>
            <div class="ef-section__desc">
                Definición de turnos, horarios y patrón 24x24 (sin pares/nonones).
            </div>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver turnos')): ?>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-teal">
                <i class="fa-solid fa-people-group"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Turnos</div>
                <div class="ef-card__desc">Turno A/B, administrativos, mixto, etc.</div>
                <a href="<?php echo e(url('turnos')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-indigo">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Horarios por turno</div>
                <div class="ef-card__desc">Horas de entrada/salida y tolerancias.</div>
                <a href="<?php echo e(url('turnos-horarios')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-secondary">
                <i class="fa-solid fa-repeat"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Patrón de servicio</div>
                <div class="ef-card__desc">Configura 24x24 por fecha inicio de ciclo.</div>
                <a href="<?php echo e(url('servicio')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver configuraciones')): ?>
    <div class="col-12 mt-2">
        <div class="ef-section">
            <div class="ef-section__title">
                <i class="fa-solid fa-gear"></i> Configuración
            </div>
            <div class="ef-section__desc">
                Usuarios, roles y permisos (Spatie).
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-dark">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Usuarios</div>
                <div class="ef-card__desc">Alta, roles y acceso al sistema.</div>
                <a href="<?php echo e(url('admin/settings/users')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Administrar
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-gray">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Roles y permisos</div>
                <div class="ef-card__desc">Control fino de permisos por módulo.</div>
                <a href="<?php echo e(url('admin/settings/roles')); ?>" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Administrar
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<style>
    :root{
        --ef-text: rgba(234,240,255,.92);
        --ef-muted: rgba(234,240,255,.65);
        --ef-stroke: rgba(255,255,255,.12);
        --ef-card: rgba(255,255,255,.08);
        --ef-card2: rgba(255,255,255,.05);
        --ef-shadow: 0 18px 55px rgba(0,0,0,.35);
        --ef-radius: 22px;
    }

    .ef-hero{
        margin: 10px 0 12px;
        border-radius: 26px;
        border: 1px solid rgba(255,255,255,.12);
        background:
            radial-gradient(700px 280px at 20% 30%, rgba(45,168,255,.20), transparent 60%),
            radial-gradient(700px 280px at 80% 30%, rgba(124,92,255,.18), transparent 60%),
            linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.04));
        box-shadow: var(--ef-shadow);
        overflow: hidden;
    }
    .ef-hero__inner{ padding: 18px 18px 16px; text-align: center; }
    .ef-hero__badge{
        display:inline-flex; align-items:center; gap:10px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(0,0,0,.18);
        border: 1px solid rgba(255,255,255,.10);
        color: rgba(234,240,255,.85);
        font-weight: 800;
        font-size: 12px;
        letter-spacing: .35px;
    }
    .ef-dot{
        width: 8px; height: 8px; border-radius: 999px;
        background: #19D38C;
        box-shadow: 0 0 0 5px rgba(25,211,140,.14);
        display:inline-block;
    }
    .ef-hero__title{
        margin-top: 10px;
        font-weight: 950;
        letter-spacing: -.6px;
        font-size: clamp(22px, 2.3vw, 30px);
        color: var(--ef-text);
    }
    .ef-hero__subtitle{
        margin-top: 6px;
        font-weight: 650;
        font-size: 13px;
        color: var(--ef-muted);
    }

    .ef-section{
        margin: 8px 0 12px;
        padding: 12px 14px;
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,.10);
        background: rgba(0,0,0,.12);
    }
    .ef-section__title{
        font-weight: 950;
        letter-spacing: -.35px;
        color: rgba(234,240,255,.92);
        display:flex;
        align-items:center;
        gap: 10px;
        font-size: 14px;
    }
    .ef-section__desc{
        margin-top: 4px;
        font-weight: 650;
        font-size: 12.5px;
        color: rgba(234,240,255,.65);
    }

    .ef-card{
        display:flex;
        gap: 14px;
        padding: 14px;
        margin-bottom: 16px;
        border-radius: var(--ef-radius);
        border: 1px solid var(--ef-stroke);
        background: linear-gradient(180deg, var(--ef-card), var(--ef-card2));
        box-shadow: 0 10px 35px rgba(0,0,0,.22);
        transition: .18s ease;
        min-height: 112px;
    }
    .ef-card:hover{
        transform: translateY(-2px);
        border-color: rgba(45,168,255,.28);
        box-shadow: 0 18px 55px rgba(0,0,0,.30);
    }

    .ef-card__icon{
        width: 52px; height: 52px;
        border-radius: 18px;
        display:grid; place-items:center;
        border: 1px solid rgba(255,255,255,.14);
        box-shadow: 0 12px 25px rgba(0,0,0,.22);
        flex: 0 0 auto;
    }
    .ef-card__icon i{
        font-size: 20px;
        color: rgba(255,255,255,.95);
    }

    .ef-card__body{ flex: 1; min-width: 0; }
    .ef-card__title{
        font-weight: 950;
        font-size: 14px;
        color: var(--ef-text);
        line-height: 1.15;
    }
    .ef-card__desc{
        margin-top: 6px;
        font-weight: 650;
        font-size: 12.5px;
        color: var(--ef-muted);
    }

    .ef-btn{
        margin-top: 10px;
        display:inline-flex;
        align-items:center;
        gap: 8px;
        border-radius: 14px;
        font-weight: 900;
        border: 1px solid rgba(45,168,255,.35) !important;
        background: linear-gradient(135deg, rgba(45,168,255,.25), rgba(124,92,255,.22)) !important;
        color: rgba(234,240,255,.95) !important;
        padding: 8px 12px;
    }
    .ef-btn:hover{
        transform: translateY(-1px);
        border-color: rgba(45,168,255,.55) !important;
        background: linear-gradient(135deg, rgba(45,168,255,.34), rgba(124,92,255,.30)) !important;
        color: rgba(234,240,255,.98) !important;
    }

    .ef-btn--ghost{
        background: rgba(0,0,0,.18) !important;
        border: 1px solid rgba(255,255,255,.12) !important;
        color: rgba(234,240,255,.88) !important;
    }
    .ef-btn--ghost:hover{
        background: rgba(0,0,0,.22) !important;
        border-color: rgba(255,255,255,.16) !important;
        transform: none;
    }

    .ef-card--soft{ opacity: .92; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/home.blade.php ENDPATH**/ ?>
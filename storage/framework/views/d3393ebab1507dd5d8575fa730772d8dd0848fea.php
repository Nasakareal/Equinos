<?php $__env->startSection('title', 'Configuraciones del Sistema'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="sv-hero">
        <div class="sv-hero__inner">
            <div class="sv-hero__badge">
                <span class="sv-dot"></span>
                <span>Administración · Control · Configuración</span>
            </div>

            <div class="sv-hero__title">
                Configuraciones del Sistema
            </div>

            <div class="sv-hero__subtitle">
                Panel de administración · Seguridad Vial · Michoacán
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver usuarios')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-orange">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Usuarios</div>
                        <div class="sv-card__desc">Alta, edición y control de accesos.</div>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver roles')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-navy">
                        <i class="fa-regular fa-flag"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Roles</div>
                        <div class="sv-card__desc">Permisos, roles y asignaciones.</div>
                        <a href="<?php echo e(route('roles.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver areas')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-teal">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Áreas</div>
                        <div class="sv-card__desc">Gestión de áreas (p. ej. Canina, Operativa, etc.).</div>
                        <a href="<?php echo e(route('areas.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver responsables')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-warning">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Responsables</div>
                        <div class="sv-card__desc">Asignación de responsables por área/personal.</div>
                        <a href="<?php echo e(route('responsables.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver estadisticas')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-success">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Estadísticas</div>
                        <div class="sv-card__desc">Reportes, exportaciones y análisis.</div>
                        <a href="<?php echo e(url('/admin/settings/estadisticas')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver incidencias')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-warning">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Incidencias</div>
                        <div class="sv-card__desc">Creación de Incidencias como Vacaciones, Permisos, etc.</div>
                        <a href="<?php echo e(route('incidencias.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver incidencias')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-warning">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Tipos de Incidencias</div>
                        <div class="sv-card__desc">Todos los tipos de incidencias.</div>
                        <a href="<?php echo e(route('incidence_types.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver configuraciones')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-primary">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Respaldos SQL</div>
                        <div class="sv-card__desc">Consulta y descarga copias de seguridad de la base de datos.</div>
                        <a href="<?php echo e(route('backups_sql.index')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver vaciados')): ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="sv-card">
                    <div class="sv-card__icon bg-danger">
                        <i class="fa-solid fa-dumpster"></i>
                    </div>
                    <div class="sv-card__body">
                        <div class="sv-card__title">Vaciar Base de Datos</div>
                        <div class="sv-card__desc">Herramienta de mantenimiento (con cuidado).</div>
                        <a href="<?php echo e(url('/admin/vaciados')); ?>" class="btn sv-btn">
                            <i class="fas fa-arrow-right"></i> Acceder
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
        --sv-text: #2f3d2f;
        --sv-muted: #68715f;
        --sv-stroke: rgba(168, 150, 121, .28);
        --sv-card: rgba(252, 248, 239, .94);
        --sv-card2: rgba(241, 234, 219, .92);
        --sv-shadow: 0 18px 40px rgba(79, 74, 58, .12);
        --sv-radius: 22px;
    }

    .sv-hero{
        margin: 10px 0 12px;
        border-radius: 26px;
        border: 1px solid rgba(168, 150, 121, .28);
        background:
            radial-gradient(700px 280px at 20% 30%, rgba(117, 150, 103, .22), transparent 60%),
            radial-gradient(700px 280px at 80% 30%, rgba(168, 122, 86, .20), transparent 60%),
            linear-gradient(180deg, rgba(250, 246, 236, .96), rgba(237, 231, 217, .94));
        box-shadow: var(--sv-shadow);
        overflow: hidden;
    }

    .sv-hero__inner{
        padding: 18px 18px 16px;
        text-align: center;
    }

    .sv-hero__badge{
        display:inline-flex;
        align-items:center;
        gap:10px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(53, 82, 60, .10);
        border: 1px solid rgba(116, 143, 105, .24);
        color: #51604e;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: .35px;
    }

    .sv-dot{
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #5e8666;
        box-shadow: 0 0 0 5px rgba(94,134,102,.14);
        display:inline-block;
    }

    .sv-hero__title{
        margin-top: 10px;
        font-weight: 950;
        letter-spacing: -.6px;
        font-size: clamp(22px, 2.3vw, 30px);
        color: var(--sv-text);
    }

    .sv-hero__subtitle{
        margin-top: 6px;
        font-weight: 650;
        font-size: 13px;
        color: var(--sv-muted);
    }

    .sv-card{
        display:flex;
        gap: 14px;
        padding: 14px;
        margin-bottom: 16px;
        border-radius: var(--sv-radius);
        border: 1px solid var(--sv-stroke);
        background: linear-gradient(180deg, var(--sv-card), var(--sv-card2));
        box-shadow: 0 12px 30px rgba(79, 74, 58, .12);
        transition: .18s ease;
        min-height: 112px;
    }

    .sv-card:hover{
        transform: translateY(-2px);
        border-color: rgba(117, 150, 103, .34);
        box-shadow: 0 18px 36px rgba(79, 74, 58, .16);
    }

    .sv-card__icon{
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display:grid;
        place-items:center;
        border: 1px solid rgba(255,255,255,.28);
        box-shadow: 0 12px 25px rgba(85, 72, 52, .14);
        flex: 0 0 auto;
    }

    .sv-card__icon i{
        font-size: 20px;
        color: rgba(255,255,255,.96);
    }

    .sv-card__body{
        flex: 1;
        min-width: 0;
    }

    .sv-card__title{
        font-weight: 950;
        font-size: 14px;
        color: var(--sv-text);
        line-height: 1.15;
    }

    .sv-card__desc{
        margin-top: 6px;
        font-weight: 650;
        font-size: 12.5px;
        color: var(--sv-muted);
        line-height: 1.4;
    }

    .sv-btn{
        margin-top: 10px;
        display:inline-flex;
        align-items:center;
        gap: 8px;
        border-radius: 14px;
        font-weight: 900;
        border: 1px solid rgba(146, 116, 87, .34) !important;
        background: linear-gradient(135deg, #cfa67e, #a37253) !important;
        color: #fff8ef !important;
        padding: 8px 12px;
        text-decoration: none !important;
        box-shadow: 0 8px 18px rgba(120, 88, 61, .14);
        transition: .18s ease;
    }

    .sv-btn:hover{
        transform: translateY(-1px);
        border-color: rgba(120, 88, 61, .46) !important;
        background: linear-gradient(135deg, #d8b28d, #b07c59) !important;
        color: #fffaf3 !important;
    }

    .sv-btn i{
        font-size: 14px;
    }

    /* Opcional: mejora visual general con fondo suave */
    .content-wrapper{
        background: linear-gradient(180deg, #f6f1e7 0%, #eee6d8 100%);
    }

    /* Para que no se vea tan lavado dentro de AdminLTE */
    .content-header h1,
    .content-header .m-0{
        color: #2f3d2f;
    }

    @media (max-width: 768px){
        .sv-card{
            min-height: auto;
            padding: 13px;
        }

        .sv-card__title{
            font-size: 13.5px;
        }

        .sv-card__desc{
            font-size: 12px;
        }

        .sv-btn{
            padding: 8px 11px;
            font-size: 13px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script> console.log("Configuraciones del Sistema con estilo SV."); </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\equinosCaninos\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>
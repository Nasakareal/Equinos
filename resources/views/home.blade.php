@extends('adminlte::page')

@section('title', 'Estado de Fuerza')

@section('content_header')
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
@stop

@section('content')
<div class="row">

    {{-- BLOQUE 1: OPERACIÓN DIARIA --}}
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

    @can('ver personal')
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-primary">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Personal</div>
                <div class="ef-card__desc">Alta, edición y consulta del estado de fuerza.</div>
                <a href="{{ url('personal') }}" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('ver incidencias')
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Incidencias</div>
                <div class="ef-card__desc">Vacaciones, licencia, franco, comisión, etc.</div>
                <a href="{{ url('incidencias') }}" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('ver reportes')
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-success">
                <i class="fa-solid fa-file-excel"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Reportes diarios</div>
                <div class="ef-card__desc">Generación y descarga de formatos diarios (6 excels).</div>
                <a href="{{ url('reportes-diarios') }}" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    @endcan

    {{-- BLOQUE 2: ARMAMENTO --}}
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

    @can('ver armamento')
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-navy">
                <i class="fa-solid fa-gun"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Inventario de armas</div>
                <div class="ef-card__desc">Alta y control de armas cortas y largas.</div>
                <a href="{{ url('armamento') }}" class="btn ef-btn">
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
                <a href="{{ url('armamento-asignaciones') }}" class="btn ef-btn">
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
    @endcan

    {{-- BLOQUE 3: TURNOS / SERVICIO --}}
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

    @can('ver turnos')
    <div class="col-lg-4 col-md-6 col-12">
        <div class="ef-card">
            <div class="ef-card__icon bg-teal">
                <i class="fa-solid fa-people-group"></i>
            </div>
            <div class="ef-card__body">
                <div class="ef-card__title">Turnos</div>
                <div class="ef-card__desc">Turno A/B, administrativos, mixto, etc.</div>
                <a href="{{ url('turnos') }}" class="btn ef-btn">
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
                <a href="{{ url('turnos-horarios') }}" class="btn ef-btn">
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
                <a href="{{ url('servicio') }}" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Abrir módulo
                </a>
            </div>
        </div>
    </div>
    @endcan

    {{-- BLOQUE 4: CONFIGURACIÓN --}}
    @can('ver configuraciones')
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
                <a href="{{ url('admin/settings/users') }}" class="btn ef-btn">
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
                <a href="{{ url('admin/settings/roles') }}" class="btn ef-btn">
                    <i class="fa-solid fa-arrow-right"></i> Administrar
                </a>
            </div>
        </div>
    </div>
    @endcan

</div>
@stop

@section('css')
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
@stop

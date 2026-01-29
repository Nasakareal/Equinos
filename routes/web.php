<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\PersonalController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\WeaponAssignmentController;
use App\Http\Controllers\IncidenceTypeController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\TurnoHorarioController;
use App\Http\Controllers\ServiceScheduleController;
use App\Http\Controllers\DailyReportController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::prefix('admin/settings')->middleware('can:ver configuraciones')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');

        Route::prefix('users')->middleware('can:ver usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->middleware('can:crear usuarios')->name('users.create');
            Route::post('/', [UserController::class, 'store'])->middleware('can:crear usuarios')->name('users.store');
            Route::get('/{user}', [UserController::class, 'show'])->middleware('can:ver usuarios')->name('users.show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('can:editar usuarios')->name('users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->middleware('can:editar usuarios')->name('users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:eliminar usuarios')->name('users.destroy');
        });

        Route::prefix('roles')->middleware('can:ver roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('can:crear roles')->name('roles.create');
            Route::post('/', [RoleController::class, 'store'])->middleware('can:crear roles')->name('roles.store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('can:editar roles')->name('roles.edit');
            Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:editar roles')->name('roles.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('can:eliminar roles')->name('roles.destroy');
            Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->middleware('can:editar roles')->name('roles.permissions');
            Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('can:editar roles')->name('roles.assignPermissions');
        });
    });

    Route::prefix('personal')->middleware('can:ver personal')->group(function () {
        Route::get('/', [PersonalController::class, 'index'])->name('personal.index');
        Route::get('/create', [PersonalController::class, 'create'])->middleware('can:crear personal')->name('personal.create');
        Route::post('/', [PersonalController::class, 'store'])->middleware('can:crear personal')->name('personal.store');
        Route::get('/{personal}', [PersonalController::class, 'show'])->middleware('can:ver personal')->name('personal.show');
        Route::get('/{personal}/edit', [PersonalController::class, 'edit'])->middleware('can:editar personal')->name('personal.edit');
        Route::put('/{personal}', [PersonalController::class, 'update'])->middleware('can:editar personal')->name('personal.update');
        Route::delete('/{personal}', [PersonalController::class, 'destroy'])->middleware('can:eliminar personal')->name('personal.destroy');
    });

    Route::prefix('armamento')->middleware('can:ver armamento')->group(function () {
        Route::get('/', [WeaponController::class, 'index'])->name('armamento.index');
        Route::get('/create', [WeaponController::class, 'create'])->middleware('can:crear armamento')->name('armamento.create');
        Route::post('/', [WeaponController::class, 'store'])->middleware('can:crear armamento')->name('armamento.store');
        Route::get('/{weapon}', [WeaponController::class, 'show'])->middleware('can:ver armamento')->name('armamento.show');
        Route::get('/{weapon}/edit', [WeaponController::class, 'edit'])->middleware('can:editar armamento')->name('armamento.edit');
        Route::put('/{weapon}', [WeaponController::class, 'update'])->middleware('can:editar armamento')->name('armamento.update');
        Route::delete('/{weapon}', [WeaponController::class, 'destroy'])->middleware('can:eliminar armamento')->name('armamento.destroy');
    });

    Route::prefix('armamento-asignaciones')->middleware('can:ver armamento')->group(function () {
        Route::get('/', [WeaponAssignmentController::class, 'index'])->name('armamento_asignaciones.index');
        Route::get('/create', [WeaponAssignmentController::class, 'create'])->middleware('can:crear armamento')->name('armamento_asignaciones.create');
        Route::post('/', [WeaponAssignmentController::class, 'store'])->middleware('can:crear armamento')->name('armamento_asignaciones.store');
        Route::get('/{weapon_assignment}', [WeaponAssignmentController::class, 'show'])->middleware('can:ver armamento')->name('armamento_asignaciones.show');
        Route::get('/{weapon_assignment}/edit', [WeaponAssignmentController::class, 'edit'])->middleware('can:editar armamento')->name('armamento_asignaciones.edit');
        Route::put('/{weapon_assignment}', [WeaponAssignmentController::class, 'update'])->middleware('can:editar armamento')->name('armamento_asignaciones.update');
        Route::delete('/{weapon_assignment}', [WeaponAssignmentController::class, 'destroy'])->middleware('can:eliminar armamento')->name('armamento_asignaciones.destroy');
    });

    Route::prefix('incidencias/tipos')->middleware('can:ver incidencias')->group(function () {
        Route::get('/', [IncidenceTypeController::class, 'index'])->name('incidence_types.index');
        Route::get('/create', [IncidenceTypeController::class, 'create'])->middleware('can:crear incidencias')->name('incidence_types.create');
        Route::post('/', [IncidenceTypeController::class, 'store'])->middleware('can:crear incidencias')->name('incidence_types.store');
        Route::get('/{incidence_type}', [IncidenceTypeController::class, 'show'])->middleware('can:ver incidencias')->name('incidence_types.show');
        Route::get('/{incidence_type}/edit', [IncidenceTypeController::class, 'edit'])->middleware('can:editar incidencias')->name('incidence_types.edit');
        Route::put('/{incidence_type}', [IncidenceTypeController::class, 'update'])->middleware('can:editar incidencias')->name('incidence_types.update');
        Route::delete('/{incidence_type}', [IncidenceTypeController::class, 'destroy'])->middleware('can:eliminar incidencias')->name('incidence_types.destroy');
    });

    Route::prefix('incidencias')->middleware('can:ver incidencias')->group(function () {
        Route::get('/', [IncidenceController::class, 'index'])->name('incidencias.index');
        Route::get('/create', [IncidenceController::class, 'create'])->middleware('can:crear incidencias')->name('incidencias.create');
        Route::post('/', [IncidenceController::class, 'store'])->middleware('can:crear incidencias')->name('incidencias.store');
        Route::get('/{incidence}', [IncidenceController::class, 'show'])->middleware('can:ver incidencias')->name('incidencias.show');
        Route::get('/{incidence}/edit', [IncidenceController::class, 'edit'])->middleware('can:editar incidencias')->name('incidencias.edit');
        Route::put('/{incidence}', [IncidenceController::class, 'update'])->middleware('can:editar incidencias')->name('incidencias.update');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->middleware('can:eliminar incidencias')->name('incidencias.destroy');
    });

    Route::prefix('turnos')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoController::class, 'index'])->name('turnos.index');
        Route::get('/create', [TurnoController::class, 'create'])->middleware('can:crear turnos')->name('turnos.create');
        Route::post('/', [TurnoController::class, 'store'])->middleware('can:crear turnos')->name('turnos.store');
        Route::get('/{turno}', [TurnoController::class, 'show'])->middleware('can:ver turnos')->name('turnos.show');
        Route::get('/{turno}/edit', [TurnoController::class, 'edit'])->middleware('can:editar turnos')->name('turnos.edit');
        Route::put('/{turno}', [TurnoController::class, 'update'])->middleware('can:editar turnos')->name('turnos.update');
        Route::delete('/{turno}', [TurnoController::class, 'destroy'])->middleware('can:eliminar turnos')->name('turnos.destroy');
    });

    Route::prefix('turnos-horarios')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoHorarioController::class, 'index'])->name('turno_horarios.index');
        Route::get('/create', [TurnoHorarioController::class, 'create'])->middleware('can:crear turnos')->name('turno_horarios.create');
        Route::post('/', [TurnoHorarioController::class, 'store'])->middleware('can:crear turnos')->name('turno_horarios.store');
        Route::get('/{turno_horario}', [TurnoHorarioController::class, 'show'])->middleware('can:ver turnos')->name('turno_horarios.show');
        Route::get('/{turno_horario}/edit', [TurnoHorarioController::class, 'edit'])->middleware('can:editar turnos')->name('turno_horarios.edit');
        Route::put('/{turno_horario}', [TurnoHorarioController::class, 'update'])->middleware('can:editar turnos')->name('turno_horarios.update');
        Route::delete('/{turno_horario}', [TurnoHorarioController::class, 'destroy'])->middleware('can:eliminar turnos')->name('turno_horarios.destroy');
    });

    Route::prefix('servicio')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [ServiceScheduleController::class, 'index'])->name('servicio.index');
        Route::get('/create', [ServiceScheduleController::class, 'create'])->middleware('can:editar turnos')->name('servicio.create');
        Route::post('/', [ServiceScheduleController::class, 'store'])->middleware('can:editar turnos')->name('servicio.store');
        Route::get('/{service_schedule}', [ServiceScheduleController::class, 'show'])->middleware('can:ver turnos')->name('servicio.show');
        Route::get('/{service_schedule}/edit', [ServiceScheduleController::class, 'edit'])->middleware('can:editar turnos')->name('servicio.edit');
        Route::put('/{service_schedule}', [ServiceScheduleController::class, 'update'])->middleware('can:editar turnos')->name('servicio.update');
        Route::delete('/{service_schedule}', [ServiceScheduleController::class, 'destroy'])->middleware('can:editar turnos')->name('servicio.destroy');
    });

    Route::prefix('reportes-diarios')->middleware('can:ver reportes')->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('daily_reports.index');
        Route::post('/generar', [DailyReportController::class, 'generar'])->middleware('can:crear reportes')->name('daily_reports.generar');
        Route::get('/{daily_report}', [DailyReportController::class, 'show'])->name('daily_reports.show');
        Route::get('/{daily_report}/descargar/{tipo}', [DailyReportController::class, 'descargar'])->middleware('can:ver reportes')->name('daily_reports.descargar');
    });

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('password.update');
});

Route::get('/prueba-404', function () {
    return response()->view('errors.404', [], 404);
});

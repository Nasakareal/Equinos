<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;

use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;

use App\Http\Controllers\Api\PersonalController;

use App\Http\Controllers\Api\WeaponController;
use App\Http\Controllers\Api\WeaponAssignmentController;

use App\Http\Controllers\Api\IncidenceTypeController;
use App\Http\Controllers\Api\IncidenceController;

use App\Http\Controllers\Api\TurnoController;
use App\Http\Controllers\Api\TurnoHorarioController;
use App\Http\Controllers\Api\ServiceScheduleController;

use App\Http\Controllers\Api\DailyReportController;

use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\PatrolAssignmentController;

/*
|--------------------------------------------------------------------------
| API Pública
|--------------------------------------------------------------------------
| Para Flutter: ping + login
*/
Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'message' => 'pong',
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| API Protegida (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Sesión / Usuario
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | Home (opcional, si tu app necesita un resumen inicial)
    |--------------------------------------------------------------------------
    */
    Route::get('/home', [HomeController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | SETTINGS (Config) - Turno actual
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/settings')->middleware('can:ver configuraciones')->group(function () {

        Route::get('/', [SettingsController::class, 'index']);

        Route::get('/turno-actual', [SettingsController::class, 'turnoActual']);
        Route::post('/turno-actual', [SettingsController::class, 'updateTurnoActual']);

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->middleware('can:ver usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/create', [UserController::class, 'create'])->middleware('can:crear usuarios');
            Route::post('/', [UserController::class, 'store'])->middleware('can:crear usuarios');
            Route::get('/{user}', [UserController::class, 'show'])->middleware('can:ver usuarios');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('can:editar usuarios');
            Route::put('/{user}', [UserController::class, 'update'])->middleware('can:editar usuarios');
            Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:eliminar usuarios');
        });

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */
        Route::prefix('roles')->middleware('can:ver roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::get('/create', [RoleController::class, 'create'])->middleware('can:crear roles');
            Route::post('/', [RoleController::class, 'store'])->middleware('can:crear roles');
            Route::get('/{role}', [RoleController::class, 'show'])->middleware('can:ver roles');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('can:editar roles');
            Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:editar roles');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('can:eliminar roles');

            Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->middleware('can:editar roles');
            Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('can:editar roles');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | PERSONAL
    |--------------------------------------------------------------------------
    */
    Route::prefix('personal')->middleware('can:ver personal')->group(function () {
        Route::get('/', [PersonalController::class, 'index']);
        Route::get('/create', [PersonalController::class, 'create'])->middleware('can:crear personal');
        Route::post('/', [PersonalController::class, 'store'])->middleware('can:crear personal');
        Route::get('/{personal}', [PersonalController::class, 'show'])->middleware('can:ver personal');
        Route::get('/{personal}/edit', [PersonalController::class, 'edit'])->middleware('can:editar personal');
        Route::put('/{personal}', [PersonalController::class, 'update'])->middleware('can:editar personal');
        Route::delete('/{personal}', [PersonalController::class, 'destroy'])->middleware('can:eliminar personal');
    });

    /*
    |--------------------------------------------------------------------------
    | ARMAMENTO
    |--------------------------------------------------------------------------
    */
    Route::prefix('armamento')->middleware('can:ver armamento')->group(function () {
        Route::get('/', [WeaponController::class, 'index']);
        Route::get('/create', [WeaponController::class, 'create'])->middleware('can:crear armamento');
        Route::post('/', [WeaponController::class, 'store'])->middleware('can:crear armamento');
        Route::get('/{weapon}', [WeaponController::class, 'show'])->middleware('can:ver armamento');
        Route::get('/{weapon}/edit', [WeaponController::class, 'edit'])->middleware('can:editar armamento');
        Route::put('/{weapon}', [WeaponController::class, 'update'])->middleware('can:editar armamento');
        Route::delete('/{weapon}', [WeaponController::class, 'destroy'])->middleware('can:eliminar armamento');
    });

    /*
    |--------------------------------------------------------------------------
    | ARMAMENTO - ASIGNACIONES
    |--------------------------------------------------------------------------
    */
    Route::prefix('armamento-asignaciones')->middleware('can:ver armamento')->group(function () {
        Route::get('/', [WeaponAssignmentController::class, 'index']);
        Route::get('/create', [WeaponAssignmentController::class, 'create'])->middleware('can:crear armamento');
        Route::post('/', [WeaponAssignmentController::class, 'store'])->middleware('can:crear armamento');
        Route::get('/{weapon_assignment}', [WeaponAssignmentController::class, 'show'])->middleware('can:ver armamento');
        Route::get('/{weapon_assignment}/edit', [WeaponAssignmentController::class, 'edit'])->middleware('can:editar armamento');
        Route::put('/{weapon_assignment}', [WeaponAssignmentController::class, 'update'])->middleware('can:editar armamento');
        Route::delete('/{weapon_assignment}', [WeaponAssignmentController::class, 'destroy'])->middleware('can:eliminar armamento');
    });

    /*
    |--------------------------------------------------------------------------
    | INCIDENCIAS - TIPOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('incidencias/tipos')->middleware('can:ver incidencias')->group(function () {
        Route::get('/', [IncidenceTypeController::class, 'index']);
        Route::get('/create', [IncidenceTypeController::class, 'create'])->middleware('can:crear incidencias');
        Route::post('/', [IncidenceTypeController::class, 'store'])->middleware('can:crear incidencias');
        Route::get('/{incidence_type}', [IncidenceTypeController::class, 'show'])->middleware('can:ver incidencias');
        Route::get('/{incidence_type}/edit', [IncidenceTypeController::class, 'edit'])->middleware('can:editar incidencias');
        Route::put('/{incidence_type}', [IncidenceTypeController::class, 'update'])->middleware('can:editar incidencias');
        Route::delete('/{incidence_type}', [IncidenceTypeController::class, 'destroy'])->middleware('can:eliminar incidencias');
    });

    /*
    |--------------------------------------------------------------------------
    | INCIDENCIAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('incidencias')->middleware('can:ver incidencias')->group(function () {
        Route::get('/', [IncidenceController::class, 'index']);
        Route::get('/create', [IncidenceController::class, 'create'])->middleware('can:crear incidencias');
        Route::post('/', [IncidenceController::class, 'store'])->middleware('can:crear incidencias');
        Route::get('/{incidence}', [IncidenceController::class, 'show'])->middleware('can:ver incidencias');
        Route::get('/{incidence}/edit', [IncidenceController::class, 'edit'])->middleware('can:editar incidencias');
        Route::put('/{incidence}', [IncidenceController::class, 'update'])->middleware('can:editar incidencias');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->middleware('can:eliminar incidencias');
    });

    /*
    |--------------------------------------------------------------------------
    | TURNOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('turnos')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoController::class, 'index']);
        Route::get('/create', [TurnoController::class, 'create'])->middleware('can:crear turnos');
        Route::post('/', [TurnoController::class, 'store'])->middleware('can:crear turnos');
        Route::get('/{turno}', [TurnoController::class, 'show'])->middleware('can:ver turnos');
        Route::get('/{turno}/edit', [TurnoController::class, 'edit'])->middleware('can:editar turnos');
        Route::put('/{turno}', [TurnoController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{turno}', [TurnoController::class, 'destroy'])->middleware('can:eliminar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | TURNOS - HORARIOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('turnos-horarios')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoHorarioController::class, 'index']);
        Route::get('/create', [TurnoHorarioController::class, 'create'])->middleware('can:crear turnos');
        Route::post('/', [TurnoHorarioController::class, 'store'])->middleware('can:crear turnos');
        Route::get('/{turno_horario}', [TurnoHorarioController::class, 'show'])->middleware('can:ver turnos');
        Route::get('/{turno_horario}/edit', [TurnoHorarioController::class, 'edit'])->middleware('can:editar turnos');
        Route::put('/{turno_horario}', [TurnoHorarioController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{turno_horario}', [TurnoHorarioController::class, 'destroy'])->middleware('can:eliminar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | SERVICIO
    |--------------------------------------------------------------------------
    */
    Route::prefix('servicio')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [ServiceScheduleController::class, 'index']);
        Route::get('/create', [ServiceScheduleController::class, 'create'])->middleware('can:editar turnos');
        Route::post('/', [ServiceScheduleController::class, 'store'])->middleware('can:editar turnos');
        Route::get('/{service_schedule}', [ServiceScheduleController::class, 'show'])->middleware('can:ver turnos');
        Route::get('/{service_schedule}/edit', [ServiceScheduleController::class, 'edit'])->middleware('can:editar turnos');
        Route::put('/{service_schedule}', [ServiceScheduleController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{service_schedule}', [ServiceScheduleController::class, 'destroy'])->middleware('can:editar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | REPORTES DIARIOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('reportes-diarios')->middleware('can:ver reportes')->group(function () {
        Route::get('/', [DailyReportController::class, 'index']);
        Route::post('/generar', [DailyReportController::class, 'generar'])->middleware('can:crear reportes');
        Route::get('/{daily_report}', [DailyReportController::class, 'show']);
        Route::get('/{daily_report}/descargar/{tipo}', [DailyReportController::class, 'descargar'])->middleware('can:ver reportes');
        Route::get('/{daily_report}/descargar/excel-armamento', [DailyReportController::class, 'descargarExcelArmamento'])->middleware('can:ver reportes');
    });

    /*
    |--------------------------------------------------------------------------
    | PATRULLAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('patrullas')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [PatrolController::class, 'index']);
        Route::get('/create', [PatrolController::class, 'create'])->middleware('can:editar turnos');
        Route::post('/', [PatrolController::class, 'store'])->middleware('can:editar turnos');
        Route::get('/{patrol}', [PatrolController::class, 'show'])->middleware('can:ver turnos');
        Route::get('/{patrol}/edit', [PatrolController::class, 'edit'])->middleware('can:editar turnos');
        Route::put('/{patrol}', [PatrolController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{patrol}', [PatrolController::class, 'destroy'])->middleware('can:editar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | PATRULLAS - ASIGNACIONES
    |--------------------------------------------------------------------------
    */
    Route::prefix('patrullas-asignaciones')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [PatrolAssignmentController::class, 'index']);
        Route::get('/create', [PatrolAssignmentController::class, 'create'])->middleware('can:editar turnos');
        Route::post('/', [PatrolAssignmentController::class, 'store'])->middleware('can:editar turnos');
        Route::get('/{patrol_assignment}', [PatrolAssignmentController::class, 'show'])->middleware('can:ver turnos');
        Route::get('/{patrol_assignment}/edit', [PatrolAssignmentController::class, 'edit'])->middleware('can:editar turnos');
        Route::put('/{patrol_assignment}', [PatrolAssignmentController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{patrol_assignment}', [PatrolAssignmentController::class, 'destroy'])->middleware('can:editar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | PERFIL / CONTRASEÑA (si tu Flutter lo va a usar)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/profile/password', [UserController::class, 'showChangePasswordForm']);
    Route::post('/profile/password', [UserController::class, 'updatePassword']);
});

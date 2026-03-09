<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PersonalController;
use App\Http\Controllers\Api\IncidenceController;
use App\Http\Controllers\Api\IncidenceTypeController;
use App\Http\Controllers\Api\WeaponController;
use App\Http\Controllers\Api\WeaponAssignmentController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\PatrolAssignmentController;
use App\Http\Controllers\Api\TurnoController;
use App\Http\Controllers\Api\DailyReportController;
use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\ActividadCatalogController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AnimalMedicalRecordController;
use App\Http\Controllers\Api\AnimalMedicalFileController;


/*
|--------------------------------------------------------------------------
| API Pública (Flutter)
|--------------------------------------------------------------------------
*/
Route::get('/ping', function () {
    return response()->json(['ok' => true, 'message' => 'pong']);
});

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| API Protegida (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/feed', [FeedController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('actividades', [ActividadController::class, 'index']);
        Route::post('actividades', [ActividadController::class, 'store']);
        Route::get('actividades/{actividad}', [ActividadController::class, 'show']);
        Route::delete('actividades/{actividad}', [ActividadController::class, 'destroy']);

        Route::get('actividad-categorias', [ActividadCatalogController::class, 'categorias']);
        Route::get('actividad-categorias/{categoriaId}/subcategorias', [ActividadCatalogController::class, 'subcategorias']);
    });

    /*
    |--------------------------------------------------------------------------
    | ANIMALES (Flutter)
    |--------------------------------------------------------------------------
    */
    Route::prefix('animales')->group(function () {

        // Listado + filtros (tipo, estatus, buscar, per_page)
        Route::get('/', [AnimalController::class, 'index']);

        // Crear
        Route::post('/', [AnimalController::class, 'store']);

        // Detalle (incluye assignments, medicalRecords+files, incidences)
        Route::get('/{animal}', [AnimalController::class, 'show']);

        // Actualizar
        Route::put('/{animal}', [AnimalController::class, 'update']);

        // Eliminar
        Route::delete('/{animal}', [AnimalController::class, 'destroy']);


        /*
        |--------------------------------------------------------------------------
        | HISTORIAL MÉDICO (Flutter)
        |--------------------------------------------------------------------------
        */
        Route::get('/{animal}/historial-medico', [AnimalMedicalRecordController::class, 'index']);
        Route::post('/{animal}/historial-medico', [AnimalMedicalRecordController::class, 'store']);

        // Si lo ocupas (tu controller lo trae), pero ojo: tu show no valida animal_id,
        // así que lo dejo como ruta opcional; si no lo usas, elimínalo.
        Route::get('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'show']);

        Route::put('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'update']);
        Route::delete('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'destroy']);


        /*
        |--------------------------------------------------------------------------
        | ARCHIVOS DE REGISTRO MÉDICO (Flutter)
        |--------------------------------------------------------------------------
        */
        // Subir archivo a un registro médico de un animal (tu store valida record pertenece al animal)
        Route::post('/{animal}/historial-medico/{record}/archivos', [AnimalMedicalFileController::class, 'store']);

        // Eliminar archivo por ID (tu destroy recibe AnimalMedicalFile $file)
        Route::delete('/historial-medico/archivos/{file}', [AnimalMedicalFileController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | PERSONAL (botón: Personal)
    |--------------------------------------------------------------------------
    */
    Route::prefix('personal')->middleware('can:ver personal')->group(function () {
        Route::get('/', [PersonalController::class, 'index']);
        Route::get('/{personal}', [PersonalController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | INCIDENCIAS (botón: Incidencias)
    |--------------------------------------------------------------------------
    */
    Route::prefix('incidencias')->middleware('can:ver incidencias')->group(function () {
        Route::get('/tipos', [IncidenceTypeController::class, 'index']);
        Route::get('/', [IncidenceController::class, 'index']);
        Route::post('/', [IncidenceController::class, 'store'])->middleware('can:crear incidencias');
        Route::get('/{incidence}', [IncidenceController::class, 'show']);
        Route::put('/{incidence}', [IncidenceController::class, 'update'])->middleware('can:editar incidencias');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->middleware('can:eliminar incidencias');
    });

    /*
    |--------------------------------------------------------------------------
    | ARMAMENTO (botón: Armamento)
    |--------------------------------------------------------------------------
    */
    Route::prefix('armamento')->middleware('can:ver armamento')->group(function () {
        Route::get('/weapons', [WeaponController::class, 'index']);
        Route::get('/weapons/{weapon}', [WeaponController::class, 'show']);
        Route::get('/asignaciones', [WeaponAssignmentController::class, 'index']);
        Route::get('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'show']);
        Route::post('/asignaciones', [WeaponAssignmentController::class, 'store'])->middleware('can:crear armamento');
        Route::put('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'update'])->middleware('can:editar armamento');
        Route::delete('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'destroy'])->middleware('can:eliminar armamento');
    });

    /*
    |--------------------------------------------------------------------------
    | PATRULLAS (botón: Patrullas)
    |--------------------------------------------------------------------------
    */
    Route::prefix('patrullas')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [PatrolController::class, 'index']);
        Route::get('/{patrol}', [PatrolController::class, 'show']);
        Route::get('/asignaciones', [PatrolAssignmentController::class, 'index']);
        Route::get('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'show']);
        Route::post('/asignaciones', [PatrolAssignmentController::class, 'store'])->middleware('can:editar turnos');
        Route::put('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'destroy'])->middleware('can:editar turnos');
    });

    /*
    |--------------------------------------------------------------------------
    | TURNOS Y SERVICIO (botón: Turnos y servicio)
    |--------------------------------------------------------------------------
    */
    Route::prefix('turnos')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoController::class, 'index']);
        Route::get('/{turno}', [TurnoController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | REPORTES DIARIOS (lo vas a usar para armamento Excel)
    |--------------------------------------------------------------------------
    */
    Route::prefix('reportes-diarios')->middleware('can:ver reportes')->group(function () {
        Route::get('/', [DailyReportController::class, 'index']);
        Route::post('/generar', [DailyReportController::class, 'generar'])->middleware('can:crear reportes');
        Route::get('/descargar/{tipo}', [DailyReportController::class, 'descargar']);
    });
});


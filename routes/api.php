<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ActividadCatalogController;
use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\AnimalAssignmentController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AnimalIncidenceController;
use App\Http\Controllers\Api\AnimalIncidenceFileController;
use App\Http\Controllers\Api\AnimalMedicalFileController;
use App\Http\Controllers\Api\AnimalMedicalRecordController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DailyReportController;
use App\Http\Controllers\Api\EquinoterapiaReporteController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\IncidenceController;
use App\Http\Controllers\Api\IncidenceTypeController;
use App\Http\Controllers\Api\PatrolAssignmentController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\PersonalController;
use App\Http\Controllers\Api\PersonalDocumentController;
use App\Http\Controllers\Api\PersonalHorarioController;
use App\Http\Controllers\Api\PersonalHorarioDetalleController;
use App\Http\Controllers\Api\PuestaDisposicionController;
use App\Http\Controllers\Api\ServiceScheduleController;
use App\Http\Controllers\Api\TurnoController;
use App\Http\Controllers\Api\TurnoHorarioController;
use App\Http\Controllers\Api\WeaponAssignmentController;
use App\Http\Controllers\Api\WeaponController;

Route::get('/ping', function () {
    return response()->json(['ok' => true, 'message' => 'pong']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/feed', [FeedController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('actividades', [ActividadController::class, 'index']);
    Route::post('actividades', [ActividadController::class, 'store']);
    Route::get('actividades/{actividad}', [ActividadController::class, 'show']);
    Route::delete('actividades/{actividad}', [ActividadController::class, 'destroy']);
    Route::get('actividad-categorias', [ActividadCatalogController::class, 'categorias']);
    Route::get('actividad-categorias/{categoriaId}/subcategorias', [ActividadCatalogController::class, 'subcategorias']);

    Route::prefix('animales')->middleware('can:ver animales')->group(function () {
        Route::get('/', [AnimalController::class, 'index']);
        Route::post('/', [AnimalController::class, 'store'])->middleware('can:crear animales');
        Route::get('/{animal}', [AnimalController::class, 'show']);
        Route::put('/{animal}', [AnimalController::class, 'update'])->middleware('can:editar animales');
        Route::delete('/{animal}', [AnimalController::class, 'destroy'])->middleware('can:eliminar animales');

        Route::prefix('{animal}/asignaciones')->group(function () {
            Route::get('/catalogos', [AnimalAssignmentController::class, 'catalogos']);
            Route::get('/', [AnimalAssignmentController::class, 'index']);
            Route::post('/', [AnimalAssignmentController::class, 'store'])->middleware('can:editar animales');
            Route::get('/{assignment}', [AnimalAssignmentController::class, 'show']);
            Route::put('/{assignment}', [AnimalAssignmentController::class, 'update'])->middleware('can:editar animales');
            Route::delete('/{assignment}', [AnimalAssignmentController::class, 'destroy'])->middleware('can:editar animales');
        });

        Route::get('/{animal}/historial-medico', [AnimalMedicalRecordController::class, 'index']);
        Route::post('/{animal}/historial-medico', [AnimalMedicalRecordController::class, 'store'])->middleware('can:editar animales');
        Route::get('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'show']);
        Route::put('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'update'])->middleware('can:editar animales');
        Route::delete('/{animal}/historial-medico/{record}', [AnimalMedicalRecordController::class, 'destroy'])->middleware('can:editar animales');
        Route::post('/{animal}/historial-medico/{record}/archivos', [AnimalMedicalFileController::class, 'store'])->middleware('can:editar animales');
        Route::delete('/historial-medico/archivos/{file}', [AnimalMedicalFileController::class, 'destroy'])->middleware('can:editar animales');

        Route::prefix('{animal}/incidencias')->group(function () {
            Route::get('/catalogos', [AnimalIncidenceController::class, 'catalogos']);
            Route::get('/', [AnimalIncidenceController::class, 'index']);
            Route::post('/', [AnimalIncidenceController::class, 'store'])->middleware('can:crear incidencias');
            Route::get('/{incidence}', [AnimalIncidenceController::class, 'show']);
            Route::put('/{incidence}', [AnimalIncidenceController::class, 'update'])->middleware('can:editar incidencias');
            Route::delete('/{incidence}', [AnimalIncidenceController::class, 'destroy'])->middleware('can:eliminar incidencias');
            Route::post('/{incidence}/archivos', [AnimalIncidenceFileController::class, 'store'])->middleware('can:editar incidencias');
        });

        Route::delete('/incidencias/archivos/{file}', [AnimalIncidenceFileController::class, 'destroy'])->middleware('can:editar incidencias');
    });

    Route::prefix('personal')->middleware('can:ver personal')->group(function () {
        Route::get('/catalogos', [PersonalController::class, 'catalogos']);
        Route::get('/', [PersonalController::class, 'index']);
        Route::post('/', [PersonalController::class, 'store'])->middleware('can:crear personal');
        Route::get('/{personal}', [PersonalController::class, 'show']);
        Route::put('/{personal}', [PersonalController::class, 'update'])->middleware('can:editar personal');
        Route::delete('/{personal}', [PersonalController::class, 'destroy'])->middleware('can:eliminar personal');

        Route::get('/{personal}/horario', [PersonalHorarioController::class, 'show']);
        Route::post('/{personal}/horario', [PersonalHorarioController::class, 'store'])->middleware('can:editar personal');
        Route::post('/{personal}/horario/{personal_horario}/detalles', [PersonalHorarioDetalleController::class, 'store'])->middleware('can:editar personal');
        Route::put('/{personal}/horario/{personal_horario}/detalles/{detalle}', [PersonalHorarioDetalleController::class, 'update'])->middleware('can:editar personal');
        Route::delete('/{personal}/horario/{personal_horario}/detalles/{detalle}', [PersonalHorarioDetalleController::class, 'destroy'])->middleware('can:editar personal');

        Route::prefix('{personal}/documentos')->group(function () {
            Route::get('/', [PersonalDocumentController::class, 'index']);
            Route::post('/', [PersonalDocumentController::class, 'store'])->middleware('can:editar personal');
            Route::get('/{documento}', [PersonalDocumentController::class, 'show']);
            Route::put('/{documento}', [PersonalDocumentController::class, 'update'])->middleware('can:editar personal');
            Route::delete('/{documento}', [PersonalDocumentController::class, 'destroy'])->middleware('can:editar personal');
            Route::get('/{documento}/descargar', [PersonalDocumentController::class, 'download']);
        });
    });

    Route::prefix('puestas-disposicion')->middleware('can:ver puestas_disposicion')->group(function () {
        Route::get('/catalogos', [PuestaDisposicionController::class, 'catalogos']);
        Route::get('/', [PuestaDisposicionController::class, 'index']);
        Route::post('/', [PuestaDisposicionController::class, 'store'])->middleware('can:crear puestas_disposicion');
        Route::get('/{puesta_disposicion}', [PuestaDisposicionController::class, 'show']);
        Route::put('/{puesta_disposicion}', [PuestaDisposicionController::class, 'update'])->middleware('can:editar puestas_disposicion');
        Route::delete('/{puesta_disposicion}', [PuestaDisposicionController::class, 'destroy'])->middleware('can:eliminar puestas_disposicion');
    });

    Route::prefix('incidencias')->middleware('can:ver incidencias')->group(function () {
        Route::get('/catalogos', [IncidenceController::class, 'catalogos']);
        Route::get('/tipos', [IncidenceTypeController::class, 'index']);
        Route::post('/tipos', [IncidenceTypeController::class, 'store'])->middleware('can:crear incidencias');
        Route::get('/tipos/{incidence_type}', [IncidenceTypeController::class, 'show']);
        Route::put('/tipos/{incidence_type}', [IncidenceTypeController::class, 'update'])->middleware('can:editar incidencias');
        Route::delete('/tipos/{incidence_type}', [IncidenceTypeController::class, 'destroy'])->middleware('can:eliminar incidencias');
        Route::get('/', [IncidenceController::class, 'index']);
        Route::post('/', [IncidenceController::class, 'store'])->middleware('can:crear incidencias');
        Route::get('/{incidence}', [IncidenceController::class, 'show']);
        Route::put('/{incidence}', [IncidenceController::class, 'update'])->middleware('can:editar incidencias');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->middleware('can:eliminar incidencias');
    });

    Route::prefix('armamento')->middleware('can:ver armamento')->group(function () {
        Route::get('/weapons', [WeaponController::class, 'index']);
        Route::post('/weapons', [WeaponController::class, 'store'])->middleware('can:crear armamento');
        Route::get('/weapons/{weapon}', [WeaponController::class, 'show']);
        Route::put('/weapons/{weapon}', [WeaponController::class, 'update'])->middleware('can:editar armamento');
        Route::delete('/weapons/{weapon}', [WeaponController::class, 'destroy'])->middleware('can:eliminar armamento');

        Route::get('/asignaciones/catalogos', [WeaponAssignmentController::class, 'catalogos']);
        Route::get('/asignaciones', [WeaponAssignmentController::class, 'index']);
        Route::post('/asignaciones', [WeaponAssignmentController::class, 'store'])->middleware('can:crear armamento');
        Route::get('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'show']);
        Route::put('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'update'])->middleware('can:editar armamento');
        Route::delete('/asignaciones/{weapon_assignment}', [WeaponAssignmentController::class, 'destroy'])->middleware('can:eliminar armamento');
    });

    Route::prefix('patrullas')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [PatrolController::class, 'index']);
        Route::post('/', [PatrolController::class, 'store'])->middleware('can:editar turnos');
        Route::get('/{patrol}', [PatrolController::class, 'show']);
        Route::put('/{patrol}', [PatrolController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{patrol}', [PatrolController::class, 'destroy'])->middleware('can:editar turnos');
        Route::get('/asignaciones', [PatrolAssignmentController::class, 'index']);
        Route::get('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'show']);
        Route::post('/asignaciones', [PatrolAssignmentController::class, 'store'])->middleware('can:editar turnos');
        Route::put('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/asignaciones/{patrol_assignment}', [PatrolAssignmentController::class, 'destroy'])->middleware('can:editar turnos');
    });

    Route::prefix('turnos')->middleware('can:ver turnos')->group(function () {
        Route::get('/', [TurnoController::class, 'index']);
        Route::post('/', [TurnoController::class, 'store'])->middleware('can:crear turnos');
        Route::get('/{turno}', [TurnoController::class, 'show']);
        Route::put('/{turno}', [TurnoController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{turno}', [TurnoController::class, 'destroy'])->middleware('can:eliminar turnos');
    });

    Route::prefix('turnos-horarios')->middleware('can:ver turnos')->group(function () {
        Route::get('/catalogos', [TurnoHorarioController::class, 'catalogos']);
        Route::get('/', [TurnoHorarioController::class, 'index']);
        Route::post('/', [TurnoHorarioController::class, 'store'])->middleware('can:crear turnos');
        Route::get('/{turno_horario}', [TurnoHorarioController::class, 'show']);
        Route::put('/{turno_horario}', [TurnoHorarioController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{turno_horario}', [TurnoHorarioController::class, 'destroy'])->middleware('can:eliminar turnos');
    });

    Route::prefix('servicio')->middleware('can:ver turnos')->group(function () {
        Route::get('/catalogos', [ServiceScheduleController::class, 'catalogos']);
        Route::get('/', [ServiceScheduleController::class, 'index']);
        Route::post('/', [ServiceScheduleController::class, 'store'])->middleware('can:editar turnos');
        Route::get('/{service_schedule}', [ServiceScheduleController::class, 'show']);
        Route::put('/{service_schedule}', [ServiceScheduleController::class, 'update'])->middleware('can:editar turnos');
        Route::delete('/{service_schedule}', [ServiceScheduleController::class, 'destroy'])->middleware('can:editar turnos');
    });

    Route::prefix('reportes-diarios')->middleware('can:ver reportes')->group(function () {
        Route::get('/', [DailyReportController::class, 'index']);
        Route::post('/generar', [DailyReportController::class, 'generar'])->middleware('can:crear reportes');
        Route::get('/descargar/{tipo}', [DailyReportController::class, 'descargar']);
    });

    Route::prefix('equinoterapias')->middleware('can:ver animales')->group(function () {
        Route::get('/', [EquinoterapiaReporteController::class, 'index']);
        Route::post('/', [EquinoterapiaReporteController::class, 'store'])->middleware('can:editar animales');
        Route::get('/{equinoterapia}', [EquinoterapiaReporteController::class, 'show']);
        Route::put('/{equinoterapia}', [EquinoterapiaReporteController::class, 'update'])->middleware('can:editar animales');
        Route::delete('/{equinoterapia}', [EquinoterapiaReporteController::class, 'destroy'])->middleware('can:editar animales');
        Route::get('/{equinoterapia}/whatsapp', [EquinoterapiaReporteController::class, 'whatsapp']);
    });
});

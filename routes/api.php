<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AnimalAssignmentController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AnimalIncidenceController;
use App\Http\Controllers\Api\AnimalIncidenceFileController;
use App\Http\Controllers\Api\AnimalMedicalFileController;
use App\Http\Controllers\Api\AnimalMedicalRecordController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EquinoterapiaReporteController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\PersonalController;
use App\Http\Controllers\Api\PersonalDocumentController;
use App\Http\Controllers\Api\PersonalHorarioController;
use App\Http\Controllers\Api\PersonalHorarioDetalleController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\ServicioReporteController;

Route::get('/ping', function () {
    return response()->json(['ok' => true, 'message' => 'pong']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/feed', [FeedController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

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

    Route::prefix('servicios')->middleware('can:ver servicios')->group(function () {
        Route::get('/', [ServicioController::class, 'index']);
        Route::post('/', [ServicioController::class, 'store'])->middleware('can:crear servicios');
        Route::get('/{servicio}', [ServicioController::class, 'show']);
        Route::put('/{servicio}', [ServicioController::class, 'update'])->middleware('can:editar servicios');
        Route::delete('/{servicio}', [ServicioController::class, 'destroy'])->middleware('can:eliminar servicios');
    });

    Route::prefix('mis-servicios')->middleware('can:ver reportes de servicios')->group(function () {
        Route::get('/', [ServicioReporteController::class, 'misServicios']);
        Route::get('/{servicio}', [ServicioReporteController::class, 'panelServicio']);

        Route::get('/{servicio}/reportes', [ServicioReporteController::class, 'index']);
        Route::post('/{servicio}/reportes', [ServicioReporteController::class, 'store'])->middleware('can:crear reportes de servicios');
        Route::get('/{servicio}/reportes/{reporte}', [ServicioReporteController::class, 'show']);
        Route::put('/{servicio}/reportes/{reporte}', [ServicioReporteController::class, 'update'])->middleware('can:editar reportes de servicios');
        Route::delete('/{servicio}/reportes/{reporte}', [ServicioReporteController::class, 'destroy'])->middleware('can:eliminar reportes de servicios');

        Route::get('/{servicio}/reportes/{reporte}/whatsapp', [ServicioReporteController::class, 'whatsapp'])->middleware('can:compartir whatsapp reportes de servicios');
        Route::get('/{servicio}/reportes/{reporte}/compartir-nativo', [ServicioReporteController::class, 'compartirNativo'])->middleware('can:compartir whatsapp reportes de servicios');

        Route::post('/{servicio}/reportes/{reporte}/fotos', [ServicioReporteController::class, 'storeFoto'])->middleware('can:subir fotos reportes de servicios');
        Route::delete('/{servicio}/reportes/{reporte}/fotos/{foto}', [ServicioReporteController::class, 'destroyFoto'])->middleware('can:eliminar fotos reportes de servicios');
    });

    Route::prefix('equinoterapias')->middleware('can:ver equinoterapias')->group(function () {
        Route::get('/', [EquinoterapiaReporteController::class, 'index']);
        Route::post('/', [EquinoterapiaReporteController::class, 'store'])->middleware('can:editar equinoterapias');
        Route::get('/{equinoterapia}', [EquinoterapiaReporteController::class, 'show']);
        Route::put('/{equinoterapia}', [EquinoterapiaReporteController::class, 'update'])->middleware('can:editar equinoterapias');
        Route::delete('/{equinoterapia}', [EquinoterapiaReporteController::class, 'destroy'])->middleware('can:editar equinoterapias');
        Route::get('/{equinoterapia}/whatsapp', [EquinoterapiaReporteController::class, 'whatsapp']);
    });
});

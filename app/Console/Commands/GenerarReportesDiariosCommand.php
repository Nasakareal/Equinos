<?php

namespace App\Console\Commands;

use App\Services\DailyReports\DailyReportsService;
use Illuminate\Console\Command;

class GenerarReportesDiariosCommand extends Command
{
    protected $signature = 'reportes-diarios:generar {fecha?} {turno_id?}';
    protected $description = 'Genera y guarda automáticamente los reportes diarios';

    public function handle(DailyReportsService $service)
    {
        $tz = 'America/Mexico_City';

        $fecha = $this->argument('fecha') ?: now($tz)->toDateString();
        $turno_id = (int) ($this->argument('turno_id') ?: 1);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $this->error('La fecha debe tener formato YYYY-MM-DD.');
            return 1;
        }

        $service->generarYGuardarTodos($fecha, $turno_id);

        $this->info("Reportes diarios generados correctamente para {$fecha}, turno {$turno_id}.");

        return 0;
    }
}

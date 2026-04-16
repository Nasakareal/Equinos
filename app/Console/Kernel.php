<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            \App\Services\TurnoActual::syncTurnoActualHoy();
        })->dailyAt('06:55');

        $schedule->command('reportes-diarios:generar')
            ->dailyAt('18:00')
            ->timezone('America/Mexico_City');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

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

        Schema::table('daily_reports', function (Blueprint $table) {
            $table->string('archivo')->nullable()->after('notas');
        });
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

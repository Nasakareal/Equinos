<?php

namespace App\Services\DailyReports\Contracts;

interface DailyReportGenerator
{
    public function tipo(): string;

    public function label(): string;

    public function extension(): string;

    public function generar(string $fecha, int $turno_id, array $params = []): string;
}

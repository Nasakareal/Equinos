<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReportRow extends Model
{
    use HasFactory;

    protected $table = 'daily_report_rows';

    protected $fillable = [
        'daily_report_id',
        'personal_id',
        'grado',
        'cuip',
        'nombre',
        'dependencia',
        'arma_corta',
        'matricula_corta',
        'arma_larga',
        'matricula_larga',
        'incidencia',
        'celular',
        'cargo',
        'crp',
        'area_sector',
        'hora_entrada',
        'firma_entrada',
        'hora_salida',
        'firma_salida',
        'despliegue_servicio',
        'observaciones',
        'orden',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime:H:i:s',
        'hora_salida' => 'datetime:H:i:s',
        'orden' => 'integer',
    ];

    public function reporte()
    {
        return $this->belongsTo(DailyReport::class, 'daily_report_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $table = 'daily_reports';

    protected $fillable = [
        'fecha',
        'tipo_reporte',
        'turno_id',
        'generado_por',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }

    public function generadoPor()
    {
        return $this->belongsTo(User::class, 'generado_por');
    }

    public function rows()
    {
        return $this->hasMany(DailyReportRow::class, 'daily_report_id');
    }
}

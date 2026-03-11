<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquinoterapiaRegistro extends Model
{
    use HasFactory;

    protected $table = 'equinoterapia_registros';

    protected $fillable = [
        'equinoterapia_reporte_id',
        'nombre_completo',
        'sexo',
        'diagnostico',
        'estatus_asistencia',
        'motivo_inasistencia',
        'es_valoracion',
    ];

    protected $casts = [
        'es_valoracion' => 'boolean',
    ];

    public function reporte()
    {
        return $this->belongsTo(EquinoterapiaReporte::class, 'equinoterapia_reporte_id');
    }
}

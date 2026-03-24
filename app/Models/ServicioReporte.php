<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioReporte extends Model
{
    use HasFactory;

    protected $table = 'servicio_reportes';

    protected $fillable = [
        'servicio_id',
        'created_by',
        'tipo_reporte',
        'fecha',
        'hora',
        'municipio',
        'lugar',
        'asunto',
        'narrativa',
        'estado_fuerza_texto',
        'acciones_a_realizar',
        'acciones_realizadas',
        'resultados',
        'datos_persona_asegurada',
        'conclusion',
        'lat',
        'lng',
        'whatsapp_texto',
    ];

    protected $casts = [
        'fecha' => 'date',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fotos()
    {
        return $this->hasMany(ServicioReporteFoto::class, 'servicio_reporte_id');
    }
}

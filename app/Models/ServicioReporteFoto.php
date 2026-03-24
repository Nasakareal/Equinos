<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioReporteFoto extends Model
{
    use HasFactory;

    protected $table = 'servicio_reporte_fotos';

    protected $fillable = [
        'servicio_reporte_id',
        'ruta',
        'nombre_original',
        'mime',
        'size',
        'descripcion',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function reporte()
    {
        return $this->belongsTo(ServicioReporte::class, 'servicio_reporte_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquinoterapiaReporte extends Model
{
    use HasFactory;

    protected $table = 'equinoterapia_reportes';

    protected $fillable = [
        'fecha',
        'valoraciones',
        'personal',
        'equinos',
        'actividades_area',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function registros()
    {
        return $this->hasMany(EquinoterapiaRegistro::class, 'equinoterapia_reporte_id');
    }
}

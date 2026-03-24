<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioEstadoFuerza extends Model
{
    use HasFactory;

    protected $table = 'servicio_estado_fuerza';

    protected $fillable = [
        'servicio_id',
        'elementos',
        'unidades',
        'remolques',
        'equinos',
        'caninos',
        'medicos_veterinarios',
        'crp',
        'observaciones',
    ];

    protected $casts = [
        'elementos' => 'integer',
        'unidades' => 'integer',
        'remolques' => 'integer',
        'equinos' => 'integer',
        'caninos' => 'integer',
        'medicos_veterinarios' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}

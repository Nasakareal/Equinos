<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioParticipante extends Model
{
    use HasFactory;

    protected $table = 'servicio_participantes';

    protected $fillable = [
        'servicio_id',
        'institucion',
        'responsable',
        'elementos',
        'vehiculos',
        'unidad_identificador',
        'descripcion',
    ];

    protected $casts = [
        'elementos' => 'integer',
        'vehiculos' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}

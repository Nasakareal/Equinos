<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TurnoHorario extends Model
{
    use HasFactory;

    protected $table = 'turno_horarios';

    protected $fillable = [
        'turno_id',
        'hora_entrada',
        'hora_salida',
        'min_tolerancia',
        'cruza_dia',
        'notas',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime:H:i:s',
        'hora_salida' => 'datetime:H:i:s',
        'cruza_dia' => 'boolean',
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalHorarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'personal_horario_detalles';

    protected $fillable = [
        'personal_horario_id',
        'dia_semana',
        'hora_entrada',
        'hora_salida',
        'cruza_dia',
        'min_tolerancia',
        'notas',
        'bloque',
    ];

    protected $casts = [
        'cruza_dia' => 'boolean',
        'min_tolerancia' => 'integer',
        'dia_semana' => 'integer',
    ];

    public function horario()
    {
        return $this->belongsTo(PersonalHorario::class, 'personal_horario_id');
    }
}

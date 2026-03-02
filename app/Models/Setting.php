<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'turno_actual_id',
        'inicio_semana_a',
        'actualizado_por',
        'actualizado_en',
    ];

    protected $casts = [
        'inicio_semana_a' => 'date',
        'actualizado_en'  => 'datetime',
    ];
}

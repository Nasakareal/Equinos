<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalHorario extends Model
{
    use HasFactory;

    protected $table = 'personal_horarios';

    protected $fillable = [
        'personal_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function detalles()
    {
        return $this->hasMany(PersonalHorarioDetalle::class, 'personal_horario_id');
    }
}

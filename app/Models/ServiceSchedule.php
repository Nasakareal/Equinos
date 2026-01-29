<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSchedule extends Model
{
    use HasFactory;

    protected $table = 'service_schedules';

    protected $fillable = [
        'personal_id',
        'turno_id',
        'tipo',
        'fecha_inicio_ciclo',
        'horas_trabajo',
        'horas_descanso',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio_ciclo' => 'date',
        'activo' => 'boolean',
        'horas_trabajo' => 'integer',
        'horas_descanso' => 'integer',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}

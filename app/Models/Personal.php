<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personals';

    protected $fillable = [
        'user_id',
        'no_empleado',
        'cuip',
        'grado',
        'nombres',
        'dependencia',
        'crp',
        'celular',
        'cargo',
        'es_responsable',
        'area_patrullaje',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'es_responsable' => 'boolean',
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidence::class, 'personal_id');
    }

    public function servicios()
    {
        return $this->hasMany(ServiceSchedule::class, 'personal_id');
    }

    public function asignacionesArmamento()
    {
        return $this->hasMany(WeaponAssignment::class, 'personal_id');
    }

    public function reportRows()
    {
        return $this->hasMany(DailyReportRow::class, 'personal_id');
    }
}

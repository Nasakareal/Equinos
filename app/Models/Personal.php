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
        'area_id',
        'crp',
        'celular',
        'cargo',
        'es_responsable',
        'siempre_visible',
        'area_patrullaje',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'es_responsable' => 'boolean',
        'siempre_visible' => 'boolean',
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function responsabilidades()
    {
        return $this->hasMany(Responsable::class, 'personal_id');
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

    public function patrolAssignments()
    {
        return $this->belongsToMany(
            PatrolAssignment::class,
            'patrol_assignment_personal',
            'personal_id',
            'patrol_assignment_id'
        )->withPivot(['rol'])->withTimestamps();
    }
}

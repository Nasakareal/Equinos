<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'turno_id',
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
        'turno_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
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

    public function servicioActivo()
    {
        return $this->hasOne(ServiceSchedule::class, 'personal_id')->where('activo', 1)->latestOfMany();
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

    public function horariosPersonales()
    {
        return $this->hasMany(PersonalHorario::class, 'personal_id');
    }

    public function horarioPersonalActivo()
    {
        return $this->hasOne(PersonalHorario::class, 'personal_id')->where('activo', 1)->latestOfMany();
    }

    public function detallesHorarioPersonal()
    {
        return $this->hasManyThrough(
            PersonalHorarioDetalle::class,
            PersonalHorario::class,
            'personal_id',
            'personal_horario_id',
            'id',
            'id'
        );
    }

    public function detallesHorarioPersonalActivo()
    {
        return $this->hasManyThrough(
            PersonalHorarioDetalle::class,
            PersonalHorario::class,
            'personal_id',
            'personal_horario_id',
            'id',
            'id'
        )->where('personal_horarios.activo', 1);
    }

    public function puestasDisposicion()
    {
        return $this->hasMany(\App\Models\PuestaDisposicion::class, 'personal_id');
    }
}

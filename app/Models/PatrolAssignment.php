<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatrolAssignment extends Model
{
    use HasFactory;

    protected $table = 'patrol_assignments';

    protected $fillable = [
        'patrol_id',
        'fecha',
        'turno_id',
        'created_by',
        'servicio',
        'zona',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function patrol()
    {
        return $this->belongsTo(Patrol::class, 'patrol_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function personals()
    {
        return $this->belongsToMany(
            Personal::class,
            'patrol_assignment_personal',
            'patrol_assignment_id',
            'personal_id'
        )->withPivot(['rol'])->withTimestamps();
    }
}

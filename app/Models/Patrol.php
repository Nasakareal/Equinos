<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patrol extends Model
{
    use HasFactory;

    protected $table = 'patrols';

    protected $fillable = [
        'numero_economico',
        'tipo',
        'placas',
        'marca',
        'modelo',
        'anio',
        'color',
        'estado',
        'observaciones',
    ];

    public function assignments()
    {
        return $this->hasMany(PatrolAssignment::class, 'patrol_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'patrulla_id');
    }
}

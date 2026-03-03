<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'personal_id',
        'turno_id',
        'patrol_id',
        'inicio',
        'fin',
        'observaciones',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }
}

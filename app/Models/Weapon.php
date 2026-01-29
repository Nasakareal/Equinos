<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weapon extends Model
{
    use HasFactory;

    protected $table = 'weapons';

    protected $fillable = [
        'tipo',
        'marca_modelo',
        'matricula',
        'estado',
        'observaciones',
    ];

    public function assignments()
    {
        return $this->hasMany(WeaponAssignment::class, 'weapon_id');
    }

    public function asignaciones()
    {
        return $this->assignments();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeaponAssignment extends Model
{
    use HasFactory;

    protected $table = 'weapon_assignments';

    protected $fillable = [
        'personal_id',
        'weapon_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'status',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_devolucion' => 'datetime',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function weapon()
    {
        return $this->belongsTo(Weapon::class, 'weapon_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'clave',
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function personals()
    {
        return $this->hasMany(Personal::class, 'area_id');
    }

    public function responsables()
    {
        return $this->hasMany(Responsable::class, 'area_id');
    }
}

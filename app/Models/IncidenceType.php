<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncidenceType extends Model
{
    use HasFactory;

    protected $table = 'incidence_types';

    protected $fillable = [
        'clave',
        'nombre',
        'afecta_servicio',
        'color',
        'activo',
    ];

    protected $casts = [
        'afecta_servicio' => 'boolean',
        'activo' => 'boolean',
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidence::class, 'incidence_type_id');
    }
}

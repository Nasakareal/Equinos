<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActividadCategoria extends Model
{
    protected $table = 'actividad_categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function subcategorias(): HasMany
    {
        return $this->hasMany(ActividadSubcategoria::class, 'actividad_categoria_id');
    }
}

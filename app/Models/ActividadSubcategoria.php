<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadSubcategoria extends Model
{
    protected $table = 'actividad_subcategorias';

    protected $fillable = [
        'actividad_categoria_id',
        'nombre',
        'slug',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(ActividadCategoria::class, 'actividad_categoria_id');
    }
}

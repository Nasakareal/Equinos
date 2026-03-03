<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'actividad_categoria_id',
        'actividad_subcategoria_id',
        'nombre',
        'cantidad',
        'foto_path',
        'foto_nombre_original',
        'foto_hash',
        'created_by',
        'updated_by',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(ActividadCategoria::class, 'actividad_categoria_id');
    }

    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(ActividadSubcategoria::class, 'actividad_subcategoria_id');
    }
}

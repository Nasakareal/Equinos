<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioRecurso extends Model
{
    use HasFactory;

    protected $table = 'servicio_recursos';

    protected $fillable = [
        'servicio_id',
        'tipo_recurso',
        'descripcion',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}

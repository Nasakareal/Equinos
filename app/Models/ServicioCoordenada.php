<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioCoordenada extends Model
{
    use HasFactory;

    protected $table = 'servicio_coordenadas';

    protected $fillable = [
        'servicio_id',
        'lat',
        'lng',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'orden' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}

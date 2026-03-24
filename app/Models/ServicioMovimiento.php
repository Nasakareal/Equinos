<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioMovimiento extends Model
{
    use HasFactory;

    protected $table = 'servicio_movimientos';

    protected $fillable = [
        'servicio_id',
        'created_by',
        'tipo_movimiento',
        'fecha',
        'hora',
        'titulo',
        'descripcion',
        'acciones_realizadas',
        'resultados',
        'observaciones',
        'lat',
        'lng',
    ];

    protected $casts = [
        'fecha' => 'date',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

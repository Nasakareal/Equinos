<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'created_by',
        'personal_id',
        'canino_id',
        'equino_id',
        'patrulla_id',
        'categoria_registro',
        'tipo_servicio',
        'fecha',
        'hora',
        'cumplio',
        'seguridad',
        'barrido_seguridad',
        'desfiles',
        'proximidad_social',
        'actos_civicos',
        'tipo_busqueda',
        'asunto',
        'lugar',
        'descripcion',
        'observaciones',
        'archivo',
        'archivo_nombre_original',
        'archivo_mime',
        'archivo_size',
    ];

    protected $casts = [
        'fecha' => 'date',
        'cumplio' => 'boolean',
        'seguridad' => 'boolean',
        'barrido_seguridad' => 'boolean',
        'desfiles' => 'boolean',
        'proximidad_social' => 'boolean',
        'actos_civicos' => 'boolean',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function canino()
    {
        return $this->belongsTo(Animal::class, 'canino_id');
    }

    public function equino()
    {
        return $this->belongsTo(Animal::class, 'equino_id');
    }

    public function patrulla()
    {
        return $this->belongsTo(Patrol::class, 'patrulla_id');
    }
}

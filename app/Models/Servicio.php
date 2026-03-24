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
        'estatus_servicio',
        'oficio_referencia',
        'memorandum_referencia',
        'unidad_clave',
        'crp',
        'objetivo_servicio',
        'folio_operativo',
        'fecha',
        'hora',
        'hora_fin',
        'cumplio',
        'seguridad',
        'barrido_seguridad',
        'desfiles',
        'proximidad_social',
        'actos_civicos',
        'tipo_busqueda',
        'asunto',
        'municipio',
        'lugar',
        'descripcion',
        'acciones_realizadas',
        'resultados',
        'conclusion_operativa',
        'comandante_responsable',
        'cargo_responsable',
        'observaciones',
        'lat',
        'lng',
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
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
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

    public function estadoFuerza()
    {
        return $this->hasOne(ServicioEstadoFuerza::class, 'servicio_id');
    }

    public function movimientos()
    {
        return $this->hasMany(ServicioMovimiento::class, 'servicio_id')
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    public function participantes()
    {
        return $this->hasMany(ServicioParticipante::class, 'servicio_id');
    }

    public function coordenadas()
    {
        return $this->hasMany(ServicioCoordenada::class, 'servicio_id')
            ->orderBy('orden');
    }

    public function recursos()
    {
        return $this->hasMany(ServicioRecurso::class, 'servicio_id');
    }

    public function reportes()
    {
        return $this->hasMany(ServicioReporte::class, 'servicio_id')->orderByDesc('fecha')->orderByDesc('hora');
    }
}

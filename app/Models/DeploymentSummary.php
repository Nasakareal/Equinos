<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeploymentSummary extends Model
{
    use HasFactory;

    protected $table = 'deployment_summaries';

    protected $fillable = [
        'fecha',
        'turno_id',
        'area',
        'total_personal',
        'total_unidades',
        'unidades_en_servicio',
        'unidades_en_base',
        'unidades_en_taller',
        'armas_cortas',
        'armas_largas',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}

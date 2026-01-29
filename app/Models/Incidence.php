<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incidence extends Model
{
    use HasFactory;

    protected $table = 'incidences';

    protected $fillable = [
        'personal_id',
        'incidence_type_id',
        'fecha_inicio',
        'fecha_fin',
        'comentario',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function tipo()
    {
        return $this->belongsTo(IncidenceType::class, 'incidence_type_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}

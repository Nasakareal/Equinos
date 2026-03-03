<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalIncidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'fecha',
        'incidence_type_id',
        'gravedad',
        'descripcion',
        'atendido_por',
        'resuelto',
        'resuelto_en',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'resuelto_en' => 'datetime',
        'resuelto' => 'boolean',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function incidenceType()
    {
        return $this->belongsTo(IncidenceType::class);
    }

    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function files()
    {
        return $this->hasMany(AnimalIncidenceFile::class);
    }
}

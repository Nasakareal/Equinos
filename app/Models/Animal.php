<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'nombre',
        'raza',
        'procedencia',
        'sexo',
        'color',
        'caracteristicas',
        'marcaje',
        'chip',
        'especialidad',
        'estatus',
        'observaciones',
        'fecha_nacimiento',
        'edad_texto',
        'forraje_kg_diario',
        'grano_kg_diario',
        'foto',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'forraje_kg_diario' => 'decimal:2',
        'grano_kg_diario' => 'decimal:2',
    ];

    protected $appends = [
        'foto_url',
    ];

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function assignments()
    {
        return $this->hasMany(AnimalAssignment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(AnimalMedicalRecord::class);
    }

    public function incidences()
    {
        return $this->hasMany(AnimalIncidence::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalMedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'fecha',
        'tipo',
        'descripcion',
        'veterinario',
        'costo',
        'proxima_cita',
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_cita' => 'date',
        'costo' => 'decimal:2',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function files()
    {
        return $this->hasMany(AnimalMedicalFile::class);
    }
}

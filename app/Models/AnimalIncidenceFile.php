<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalIncidenceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_incidence_id',
        'archivo',
        'tipo',
    ];

    public function incidence()
    {
        return $this->belongsTo(AnimalIncidence::class, 'animal_incidence_id');
    }
}

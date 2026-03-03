<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalMedicalFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_medical_record_id',
        'archivo',
        'tipo',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(AnimalMedicalRecord::class, 'animal_medical_record_id');
    }
}

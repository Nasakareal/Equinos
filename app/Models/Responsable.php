<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    use HasFactory;

    protected $table = 'responsables';

    protected $fillable = [
        'personal_id',
        'area_id',
        'nivel',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function scopeActivos($q)
    {
        return $q->where('activo', 1);
    }

    public function scopeGeneral($q)
    {
        return $q->where('nivel', 'GENERAL');
    }

    public function scopeDeArea($q, $areaId)
    {
        return $q->where('nivel', 'AREA')->where('area_id', $areaId);
    }
}

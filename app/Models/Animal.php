<?php

namespace App\Models;

use Carbon\Carbon;
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
        'condicion_reproductiva',
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
        'edad_calculada',
    ];

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function getEdadCalculadaAttribute(): ?string
    {
        if (empty($this->fecha_nacimiento)) {
            return $this->edad_texto ?: null;
        }

        $fecha = Carbon::parse($this->fecha_nacimiento)->startOfDay();
        $hoy = Carbon::today();

        if ($fecha->greaterThan($hoy)) {
            return null;
        }

        $diff = $fecha->diff($hoy);

        $anios = $diff->y;
        $meses = $diff->m;

        if ($anios > 0 && $meses > 0) {
            return str_pad((string) $anios, 2, '0', STR_PAD_LEFT) . ' AÑOS ' .
                   str_pad((string) $meses, 2, '0', STR_PAD_LEFT) . ' MESES';
        }

        if ($anios > 0) {
            return str_pad((string) $anios, 2, '0', STR_PAD_LEFT) . ' AÑOS';
        }

        return str_pad((string) $meses, 2, '0', STR_PAD_LEFT) . ' MESES';
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

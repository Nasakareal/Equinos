<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PuestaDisposicion extends Model
{
    use HasFactory;

    protected $table = 'puestas_disposicions';

    protected $fillable = [
        'personal_id',
        'hecho_id',
        'anio',
        'folio',
        'archivo_pdf',
        'observaciones',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'anio' => 'integer',
        'personal_id' => 'integer',
        'hecho_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $appends = [
        'archivo_pdf_url',
    ];

    public function getArchivoPdfUrlAttribute(): ?string
    {
        if (!$this->archivo_pdf) return null;
        return Storage::disk('public')->url($this->archivo_pdf);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}

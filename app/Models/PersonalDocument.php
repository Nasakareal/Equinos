<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDocument extends Model
{
    use HasFactory;

    protected $table = 'personal_documents';

    protected $fillable = [
        'personal_id',
        'tipo_documento',
        'titulo',
        'descripcion',
        'archivo',
        'nombre_original',
        'mime_type',
        'extension',
        'tamano',
        'fecha_documento',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'fecha_documento' => 'date',
        'activo' => 'boolean',
        'tamano' => 'integer',
        'personal_id' => 'integer',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}

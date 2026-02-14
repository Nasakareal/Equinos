<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['turno_actual_id', 'actualizado_por', 'actualizado_en'];
}
